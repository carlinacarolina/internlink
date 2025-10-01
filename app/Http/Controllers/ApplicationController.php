<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\MajorStaffAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Browsershot\Browsershot;

class ApplicationController extends Controller
{
    private const STUDENT_ACCESS_OPTIONS = ['true', 'false', 'any'];

    private function schoolIdOrFail(): int
    {
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        return $schoolId;
    }

    private function statusOptions(): array
    {
        $driver = DB::getDriverName();
        if ($driver === 'pgsql') {
            return collect(DB::select("SELECT unnest(enum_range(NULL::application_status_enum)) AS status"))
                ->pluck('status')
                ->all();
        }

        return ['submitted', 'under_review', 'accepted', 'rejected', 'cancelled'];
    }

    private function periodOptions(int $schoolId): array
    {
        return DB::table('periods')
            ->select('id', 'year', 'term')
            ->where('school_id', $schoolId)
            ->orderByDesc('year')
            ->orderByDesc('term')
            ->get()
            ->map(fn ($period) => [
                'id' => $period->id,
                'label' => $period->year . ': ' . $period->term,
            ])
            ->all();
    }

    private function periodsByInstitution(array $institutionIds, int $schoolId): array
    {
        if (empty($institutionIds)) {
            return [];
        }

        return DB::table('institution_quotas as iq')
            ->join('periods as p', 'p.id', '=', 'iq.period_id')
            ->whereIn('iq.institution_id', $institutionIds)
            ->where('iq.school_id', $schoolId)
            ->where('p.school_id', $schoolId)
            ->orderByDesc('p.year')
            ->orderByDesc('p.term')
            ->get([
                'iq.institution_id',
                'p.id as period_id',
                'p.year',
                'p.term',
            ])
            ->groupBy('institution_id')
            ->map(function ($rows) {
                return $rows
                    ->unique('period_id')
                    ->map(function ($row) {
                        return [
                            'id' => (int) $row->period_id,
                            'label' => $row->year . ': ' . $row->term,
                        ];
                    })
                    ->values()
                    ->all();
            })
            ->all();
    }

    private function resolvePrintableApplication(int $id)
    {
        $schoolId = $this->schoolIdOrFail();

        $application = DB::table('application_details_view')
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();
        abort_if(!$application, 404);

        $studentId = $this->currentStudentId();
        if (session('role') === 'student' && $application->student_id !== $studentId) {
            abort(401);
        }

        return $application;
    }

    public function index(Request $request)
    {
        $role = session('role');
        $studentId = $this->currentStudentId();
        $schoolId = $this->schoolIdOrFail();

        $query = DB::table('application_details_view')->where('school_id', $schoolId);
        if ($role === 'student' && $studentId) {
            $query->where('student_id', $studentId);
        }

        $filters = [];
        $statuses = $this->statusOptions();

        if ($studentName = trim((string) $request->query('student_name'))) {
            $query->whereRaw('LOWER(student_name) LIKE ?', ['%' . strtolower($studentName) . '%']);
            $filters['student_name'] = 'Student: ' . $studentName;
        }

        if ($institutionName = trim((string) $request->query('institution_name'))) {
            $query->whereRaw('LOWER(institution_name) LIKE ?', ['%' . strtolower($institutionName) . '%']);
            $filters['institution_name'] = 'Institution: ' . $institutionName;
        }

        if ($periodId = $request->query('period_id')) {
            $query->where('period_id', $periodId);
            $period = DB::table('periods')
                ->select('year', 'term')
                ->where('id', $periodId)
                ->where('school_id', $schoolId)
                ->first();
            if ($period) {
                $filters['period_id'] = 'Period: ' . $period->year . ': ' . $period->term;
            }
        }

        if ($status = $request->query('status')) {
            if (in_array($status, $statuses, true)) {
                $query->where('status', $status);
                $filters['status'] = 'Status: ' . $status;
            }
        }

        if (($studentAccess = $request->query('student_access')) && in_array($studentAccess, ['true', 'false'], true)) {
            $query->where('student_access', $studentAccess === 'true');
            $filters['student_access'] = 'Student Access: ' . ucfirst($studentAccess);
        }

        if ($submittedAt = $request->query('submitted_at')) {
            $query->whereDate('submitted_at', $submittedAt);
            $filters['submitted_at'] = 'Submitted At: ' . $submittedAt;
        }

        if (($hasNotes = $request->query('has_notes')) && in_array($hasNotes, ['true', 'false'], true)) {
            if ($hasNotes === 'true') {
                $query->whereNotNull('application_notes')->where('application_notes', '!=', '');
                $filters['has_notes'] = 'Notes: True';
            } else {
                $query->where(function ($q) {
                    $q->whereNull('application_notes')->orWhere('application_notes', '');
                });
                $filters['has_notes'] = 'Notes: False';
            }
        }

        if ($search = trim((string) $request->query('q'))) {
            $searchTerm = strtolower($search);
            $driver = DB::getDriverName();
            $yearExpr = $driver === 'pgsql' ? 'period_year::text' : 'CAST(period_year AS CHAR)';
            $termExpr = $driver === 'pgsql' ? 'period_term::text' : 'CAST(period_term AS CHAR)';
            if ($driver === 'pgsql') {
                $submittedExpr = "TO_CHAR(submitted_at, 'YYYY-MM-DD')";
            } elseif ($driver === 'sqlite') {
                $submittedExpr = "STRFTIME('%Y-%m-%d', submitted_at)";
            } else {
                $submittedExpr = "DATE_FORMAT(submitted_at, '%Y-%m-%d')";
            }
            $studentAccessExpr = "CASE WHEN student_access THEN 'true' ELSE 'false' END";

            $statusExpr = $driver === 'pgsql' ? 'status::text' : 'status';

            $query->where(function ($q) use ($searchTerm, $yearExpr, $termExpr, $submittedExpr, $studentAccessExpr, $statusExpr) {
                $q->whereRaw('LOWER(student_name) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(institution_name) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER($yearExpr) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER($termExpr) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER($statusExpr) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER($studentAccessExpr) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER($submittedExpr) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        $applications = $query
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('application.index', [
            'applications' => $applications,
            'filters' => $filters,
            'statuses' => $statuses,
            'periods' => $this->periodOptions($schoolId),
        ]);
    }

    public function create()
    {
        $role = session('role');
        $studentId = $this->currentStudentId();
        $schoolId = $this->schoolIdOrFail();

        $studentsWithoutApplication = DB::table('student_details_view as sdv')
            ->leftJoin('applications as apps', function ($join) use ($schoolId) {
                $join->on('apps.student_id', '=', 'sdv.id')
                    ->where('apps.school_id', '=', $schoolId);
            })
            ->where('sdv.school_id', $schoolId)
            ->whereNull('apps.id')
            ->select('sdv.id', 'sdv.name', 'sdv.major')
            ->orderBy('sdv.name')
            ->get();

        if ($role === 'student') {
            abort_unless($studentId, 401);

            $hasExisting = Application::where('school_id', $schoolId)
                ->where('student_id', $studentId)
                ->exists();
            if ($hasExisting) {
                return redirect($this->schoolRoute('applications'))->withErrors([
                    'student_ids' => 'You already have an application.',
                ]);
            }

            $students = DB::table('student_details_view')
                ->select('id', 'name', 'major')
                ->where('id', $studentId)
                ->where('school_id', $schoolId)
                ->orderBy('name')
                ->get();

            // Student cannot use bulk helper; keep dataset minimal for clarity.
            $studentsWithoutApplication = collect();
        } else {
            $students = $studentsWithoutApplication;
        }

        $institutions = DB::table('institution_details_view')
            ->select('id', 'name')
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $institutionPeriods = $this->periodsByInstitution(
            $institutions->pluck('id')->map(fn ($id) => (int) $id)->all(),
            $schoolId
        );

        $majorStaff = DB::table('major_staff_details_view')
            ->select('major', 'major_slug', 'name', 'email', 'phone', 'supervisor_number')
            ->where('school_id', $schoolId)
            ->orderBy('major')
            ->get();

        return view('application.create', [
            'students' => $students,
            'studentsWithoutApplication' => $studentsWithoutApplication,
            'institutions' => $institutions,
            'periods' => $this->periodOptions($schoolId),
            'institutionPeriods' => $institutionPeriods,
            'statuses' => $this->statusOptions(),
            'canSetStudentAccess' => $role !== 'student',
            'isStudent' => $role === 'student',
            'majorStaff' => $majorStaff,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $role = session('role');
        $statuses = $this->statusOptions();
        $schoolId = $this->schoolIdOrFail();

        $rules = [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'distinct',
                'integer',
                Rule::exists('students', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'institution_id' => [
                'required',
                Rule::exists('institutions', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'period_id' => [
                'required',
                Rule::exists('periods', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'status' => ['required', Rule::in($statuses)],
            'submitted_at' => 'required|date',
            'notes' => 'nullable|string',
            'apply_missing' => 'nullable|boolean',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => ['nullable', 'date', 'after_or_equal:planned_start_date'],
        ];

        if ($role === 'student') {
            $studentId = $this->currentStudentId();
            abort_unless($studentId, 401);

            $rules['student_ids'] = 'required|array|size:1';
            $rules['student_ids.*'] = 'integer|in:' . $studentId;
        } else {
            $rules['student_access'] = ['required', Rule::in(self::STUDENT_ACCESS_OPTIONS)];
        }

        $validated = $request->validate($rules);

        $selectedStudentIds = collect($validated['student_ids'])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($role === 'student') {
            $studentId = $this->currentStudentId();
            abort_unless($studentId, 401);

            $hasApplication = Application::where('school_id', $schoolId)
                ->where('student_id', $studentId)
                ->exists();
            if ($hasApplication) {
                return redirect($this->schoolRoute('applications'))
                    ->withErrors([
                    'student_ids' => 'You already have an application.',
                ]);
            }

            if ($selectedStudentIds !== [$studentId]) {
                return back()->withErrors([
                    'student_ids' => 'Invalid student selected for creation.',
                ])->withInput();
            }
        }

        $institutionId = (int) $validated['institution_id'];
        $periodId = (int) $validated['period_id'];

        if (empty($selectedStudentIds)) {
            return back()->withErrors([
                'student_ids' => 'Select at least one student.',
            ])->withInput();
        }

        $initialRecords = DB::table('student_details_view')
            ->select('id', 'major')
            ->where('school_id', $schoolId)
            ->whereIn('id', $selectedStudentIds)
            ->get();

        if ($initialRecords->count() !== count($selectedStudentIds)) {
            return back()->withErrors([
                'student_ids' => 'One or more students could not be found.',
            ])->withInput();
        }

        $missingMajor = $initialRecords->first(fn ($record) => trim((string) $record->major) === '');
        if ($missingMajor) {
            return back()->withErrors([
                'student_ids' => 'All students must have a major before creating an application.',
            ])->withInput();
        }

        $initialMajorSlug = $initialRecords
            ->map(fn ($record) => MajorStaffAssignment::slugFromMajor((string) $record->major))
            ->unique();

        if ($initialMajorSlug->count() !== 1) {
            return back()->withErrors([
                'student_ids' => 'Selected students must belong to the same major.',
            ])->withInput();
        }

        $majorSlug = $initialMajorSlug->first();

        $targetStudentIds = $selectedStudentIds;

        if ($role !== 'student' && $request->boolean('apply_missing')) {
            $missingStudents = DB::table('student_details_view as sdv')
                ->leftJoin('applications as apps', function ($join) use ($schoolId) {
                    $join->on('apps.student_id', '=', 'sdv.id')
                        ->where('apps.school_id', '=', $schoolId);
                })
                ->where('sdv.school_id', $schoolId)
                ->whereNull('apps.id')
                ->select('sdv.id', 'sdv.major')
                ->get();

            $eligibleMissing = $missingStudents
                ->filter(fn ($student) => trim((string) $student->major) !== '' && MajorStaffAssignment::slugFromMajor((string) $student->major) === $majorSlug)
                ->pluck('id')
                ->map(fn ($id) => (int) $id)
                ->all();

            $targetStudentIds = array_values(array_unique(array_merge($targetStudentIds, $eligibleMissing)));
        }

        if (empty($targetStudentIds)) {
            return back()->withErrors([
                'student_ids' => 'Select at least one student.',
            ])->withInput();
        }

        $allStudentRecords = DB::table('student_details_view')
            ->select('id', 'major')
            ->where('school_id', $schoolId)
            ->whereIn('id', $targetStudentIds)
            ->get();

        if ($allStudentRecords->count() !== count($targetStudentIds)) {
            return back()->withErrors([
                'student_ids' => 'One or more selected students are invalid for this school.',
            ])->withInput();
        }

        $invalidMajor = $allStudentRecords->first(fn ($record) => trim((string) $record->major) === '');
        if ($invalidMajor) {
            return back()->withErrors([
                'student_ids' => 'All selected students must have a major.',
            ])->withInput();
        }

        $allMajorSlugs = $allStudentRecords
            ->map(fn ($record) => MajorStaffAssignment::slugFromMajor((string) $record->major))
            ->unique();

        if ($allMajorSlugs->count() !== 1) {
            return back()->withErrors([
                'student_ids' => 'All selected students must belong to the same major.',
            ])->withInput();
        }

        $majorSlug = $allMajorSlugs->first();
        $majorName = $allStudentRecords->pluck('major')->first();

        $staffAssignment = DB::table('major_staff_assignments')
            ->where('school_id', $schoolId)
            ->where('major_slug', $majorSlug)
            ->first();

        if (!$staffAssignment) {
            return back()->withErrors([
                'student_ids' => 'No staff contact is assigned for the selected major (' . $majorName . ').',
            ])->withInput();
        }

        $duplicateStudents = Application::where('school_id', $schoolId)
            ->whereIn('student_id', $targetStudentIds)
            ->where('institution_id', $institutionId)
            ->where('period_id', $periodId)
            ->pluck('student_id')
            ->all();

        if (!empty($duplicateStudents)) {
            return back()->withErrors([
                'student_ids' => 'An application already exists for one or more selected students with the chosen institution and period.',
            ])->withInput();
        }

        $studentAccess = false;
        if ($role !== 'student') {
            $input = $validated['student_access'] ?? 'any';
            $studentAccess = $input === 'true';
        }

        DB::transaction(function () use ($targetStudentIds, $institutionId, $periodId, $validated, $studentAccess, $schoolId) {
            foreach ($targetStudentIds as $studentId) {
                Application::create([
                    'school_id' => $schoolId,
                    'student_id' => $studentId,
                    'institution_id' => $institutionId,
                    'period_id' => $periodId,
                    'status' => $validated['status'],
                    'student_access' => $studentAccess,
                    'submitted_at' => $validated['submitted_at'],
                    'planned_start_date' => $validated['planned_start_date'] ?? null,
                    'planned_end_date' => $validated['planned_end_date'] ?? null,
                    'notes' => $validated['notes'] ?? null,
                ]);
            }
        });

        $count = count($targetStudentIds);
        $message = $count === 1
            ? 'Application created successfully.'
            : "Applications created successfully for {$count} students.";

        return redirect($this->schoolRoute('applications'))->with('status', $message);
    }

    public function show(string $school, int $id)
    {
        $application = $this->resolvePrintableApplication($id);

        return view('application.show', [
            'application' => $application,
        ]);
    }

    public function pdf(string $school, int $id)
    {
        $application = $this->resolvePrintableApplication($id);
        $generatedAt = now();

        $html = $this->renderPdfHtml($application, $generatedAt);
        $pdfBinary = $this->generatePdfBinary($html);
        $fileName = $this->pdfFileName($application);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
        ]);
    }

    public function pdfPrint(string $school, int $id)
    {
        $application = $this->resolvePrintableApplication($id);
        $generatedAt = now();

        $html = $this->renderPdfHtml($application, $generatedAt);
        $pdfBinary = $this->generatePdfBinary($html);
        $fileName = $this->pdfFileName($application);

        return response($pdfBinary, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function renderPdfHtml(object $application, $generatedAt): string
    {
        $school = DB::table('school_details_view')->where('id', $application->school_id)->first();
        abort_if(!$school, 404, 'School context missing.');

        $majorSlug = MajorStaffAssignment::slugFromMajor((string) $application->student_major);

        $peers = DB::table('application_details_view')
            ->where('school_id', $application->school_id)
            ->where('institution_id', $application->institution_id)
            ->where('period_id', $application->period_id)
            ->orderBy('student_name')
            ->get()
            ->filter(fn ($row) => MajorStaffAssignment::slugFromMajor((string) $row->student_major) === $majorSlug)
            ->values();

        return view('application.pdf', [
            'application' => $application,
            'generatedAt' => $generatedAt,
            'school' => $school,
            'students' => $peers,
        ])->render();
    }

    private function generatePdfBinary(string $html): string
    {
        return Browsershot::html($html)
            ->format('A4')
            ->margins(20, 16, 20, 16)
            ->showBackground()
            ->waitUntilNetworkIdle()
            ->setOption('args', ['--no-sandbox', '--disable-setuid-sandbox'])
            ->pdf();
    }

    private function pdfFileName(object $application): string
    {
        $safeName = preg_replace('/[^A-Za-z0-9-_]+/', '_', (string) ($application->student_name ?? 'student'));

        return sprintf('application_%s.pdf', strtolower(trim($safeName, '_')) ?: 'student');
    }

    public function edit(string $school, int $id)
    {
        $schoolId = $this->schoolIdOrFail();

        $application = DB::table('application_details_view')
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();
        abort_if(!$application, 404);

        $role = session('role');
        $studentId = $this->currentStudentId();

        if ($role === 'student') {
            if ($application->student_id !== $studentId || !$application->student_access) {
                abort(401);
            }
        }

        $studentsForInstitution = DB::table('application_details_view')
            ->select('student_id', 'student_name', 'student_major')
            ->where('institution_id', $application->institution_id)
            ->where('school_id', $schoolId)
            ->distinct()
            ->orderBy('student_name')
            ->get();

        $students = $role === 'student'
            ? $studentsForInstitution->where('student_id', $application->student_id)->values()
            : $studentsForInstitution;

        $institutions = DB::table('institution_details_view')
            ->select('id', 'name')
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $institutionPeriods = $this->periodsByInstitution(
            $institutions->pluck('id')->map(fn ($id) => (int) $id)->all(),
            $schoolId
        );

        $majorStaff = DB::table('major_staff_details_view')
            ->select('major', 'major_slug', 'name', 'email', 'phone', 'supervisor_number')
            ->where('school_id', $schoolId)
            ->orderBy('major')
            ->get();

        return view('application.edit', [
            'application' => $application,
            'students' => $students,
            'allStudentsForInstitution' => $studentsForInstitution,
            'institutions' => $institutions,
            'periods' => $this->periodOptions($schoolId),
            'institutionPeriods' => $institutionPeriods,
            'statuses' => $this->statusOptions(),
            'canSetStudentAccess' => $role !== 'student',
            'isStudent' => $role === 'student',
            'majorStaff' => $majorStaff,
        ]);
    }

    public function update(Request $request, string $school, int $id): RedirectResponse
    {
        $schoolId = $this->schoolIdOrFail();

        $application = Application::where('school_id', $schoolId)->findOrFail($id);
        $viewRecord = DB::table('application_details_view')
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();
        abort_if(!$viewRecord, 404);

        $role = session('role');
        $studentId = $this->currentStudentId();

        if ($role === 'student') {
            if ($viewRecord->student_id !== $studentId || !$viewRecord->student_access) {
                abort(401);
            }
        }

        $statuses = $this->statusOptions();

        $rules = [
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => [
                'distinct',
                Rule::exists('students', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'institution_id' => [
                'required',
                Rule::exists('institutions', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'period_id' => [
                'required',
                Rule::exists('periods', 'id')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'status' => ['required', Rule::in($statuses)],
            'submitted_at' => 'required|date',
            'notes' => 'nullable|string',
            'apply_all' => 'nullable|boolean',
            'planned_start_date' => 'nullable|date',
            'planned_end_date' => ['nullable', 'date', 'after_or_equal:planned_start_date'],
        ];

        if ($role !== 'student') {
            $rules['student_access'] = ['required', Rule::in(self::STUDENT_ACCESS_OPTIONS)];
        }

        $validated = $request->validate($rules);

        $selectedStudentIds = collect($validated['student_ids'])->map(fn ($value) => (int) $value)->all();
        if (!in_array($application->student_id, $selectedStudentIds, true)) {
            return back()->withErrors([
                'student_ids' => 'Original student must remain selected.',
            ])->withInput();
        }

        if ($role === 'student') {
            if ($selectedStudentIds !== [$application->student_id]) {
                return back()->withErrors([
                    'student_ids' => 'Students cannot modify other student assignments.',
                ])->withInput();
            }
        }

        $applyAll = $role !== 'student' && $request->boolean('apply_all');

        if (!$applyAll) {
            $existing = Application::where('school_id', $schoolId)
                ->where('institution_id', $application->institution_id)
                ->whereIn('student_id', $selectedStudentIds)
                ->pluck('student_id')
                ->all();

            if (count($existing) !== count($selectedStudentIds)) {
                return back()->withErrors([
                    'student_ids' => 'All selected students must already have an application with this institution.',
                ])->withInput();
            }
        }

        $targetQuery = Application::where('school_id', $schoolId)
            ->where('institution_id', $application->institution_id);
        if (!$applyAll) {
            $targetQuery->whereIn('student_id', $selectedStudentIds);
        }

        $targetApplications = $targetQuery->get(['id', 'student_id']);
        $targetIds = $targetApplications->pluck('id')->all();

        foreach ($targetApplications as $target) {
            $duplicate = Application::where('school_id', $schoolId)
                ->where('student_id', $target->student_id)
                ->where('institution_id', $validated['institution_id'])
                ->where('period_id', $validated['period_id'])
                ->whereNotIn('id', $targetIds)
                ->exists();

            if ($duplicate) {
                return back()->withErrors([
                    'student_ids' => 'Duplicate application detected for one of the selected students.',
                ])->withInput();
            }
        }

        $targetStudentIdsFinal = $targetApplications->pluck('student_id')->map(fn ($id) => (int) $id)->all();

        $studentRecords = DB::table('student_details_view')
            ->select('id', 'major')
            ->where('school_id', $schoolId)
            ->whereIn('id', $targetStudentIdsFinal)
            ->get();

        if ($studentRecords->count() !== count($targetStudentIdsFinal)) {
            return back()->withErrors([
                'student_ids' => 'One or more students are invalid for this school.',
            ])->withInput();
        }

        $missingMajor = $studentRecords->first(fn ($record) => trim((string) $record->major) === '');
        if ($missingMajor) {
            return back()->withErrors([
                'student_ids' => 'All selected students must have a major.',
            ])->withInput();
        }

        $majorSlugs = $studentRecords
            ->map(fn ($record) => MajorStaffAssignment::slugFromMajor((string) $record->major))
            ->unique();

        if ($majorSlugs->count() !== 1) {
            return back()->withErrors([
                'student_ids' => 'Selected students must share the same major.',
            ])->withInput();
        }

        $majorSlug = $majorSlugs->first();
        $majorName = $studentRecords->pluck('major')->first();

        $staffAssignment = DB::table('major_staff_assignments')
            ->where('school_id', $schoolId)
            ->where('major_slug', $majorSlug)
            ->first();

        if (!$staffAssignment) {
            return back()->withErrors([
                'student_ids' => 'No staff contact is assigned for the selected major (' . $majorName . ').',
            ])->withInput();
        }

        $studentAccess = $application->student_access;
        if ($role !== 'student') {
            $input = $validated['student_access'] ?? 'any';
            $studentAccess = $input === 'true' ? true : ($input === 'false' ? false : $studentAccess);
        }

        $updateData = [
            'institution_id' => $validated['institution_id'],
            'period_id' => $validated['period_id'],
            'status' => $validated['status'],
            'submitted_at' => $validated['submitted_at'],
            'planned_start_date' => $validated['planned_start_date'] ?? null,
            'planned_end_date' => $validated['planned_end_date'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ];

        if ($role !== 'student') {
            $updateData['student_access'] = $studentAccess;
        }

        Application::where('school_id', $schoolId)->whereIn('id', $targetIds)->update($updateData);

        return redirect($this->schoolRoute('applications/' . $id . '/read'))->with('status', 'Application updated successfully.');
    }

    public function destroy(string $school, int $id): RedirectResponse
    {
        $schoolId = $this->schoolIdOrFail();

        $application = Application::where('school_id', $schoolId)->findOrFail($id);
        $viewRecord = DB::table('application_details_view')
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();
        abort_if(!$viewRecord, 404);

        $role = session('role');
        $studentId = $this->currentStudentId();

        if ($role === 'student') {
            if ($viewRecord->student_id !== $studentId || !$viewRecord->student_access) {
                abort(401);
            }
        }

        $application->delete();

        return redirect($this->schoolRoute('applications'))->with('status', 'Application deleted successfully.');
    }
}
