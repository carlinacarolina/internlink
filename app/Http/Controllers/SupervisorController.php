<?php

namespace App\Http\Controllers;

use App\Models\SchoolMajor;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SupervisorController extends Controller
{
    /**
     * Columns searched and displayed. Adjust here if needed.
     */
    private const SEARCH_COLUMNS = ['name', 'email', 'phone', 'department'];

    public function index(Request $request)
    {
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        $query = DB::table('supervisor_details_view as sv')
            ->select(
                'sv.id',
                'sv.name',
                'sv.email',
                'sv.phone',
                'sv.department',
                'sv.email_verified_at',
                'sv.notes',
                'sv.photo'
            )
            ->where('sv.school_id', $schoolId);
        if (session('role') === 'student') {
            $query->join('monitoring_logs as ml', 'sv.id', '=', 'ml.supervisor_id')
                ->join('internships as it', 'ml.internship_id', '=', 'it.id')
                ->where('ml.school_id', $schoolId)
                ->where('it.school_id', $schoolId)
                ->where('it.student_id', $this->currentStudentId())
                ->distinct();
        } elseif (session('role') === 'supervisor') {
            $query->where('sv.id', $this->currentSupervisorId());
        }

        $filters = [];

        if ($name = trim($request->query('name', ''))) {
            $query->where('sv.name', 'like', '%' . $name . '%');
            $filters[] = [
                'param' => 'name',
                'label' => 'Name: ' . $name,
            ];
        }

        if ($email = trim($request->query('email', ''))) {
            $query->where('sv.email', 'like', '%' . $email . '%');
            $filters[] = [
                'param' => 'email',
                'label' => 'Email: ' . $email,
            ];
        }

        if ($phone = trim($request->query('phone', ''))) {
            $query->where('sv.phone', 'like', '%' . $phone . '%');
            $filters[] = [
                'param' => 'phone',
                'label' => 'Phone: ' . $phone,
            ];
        }

        if ($department = trim($request->query('department', ''))) {
            $query->where('sv.department', 'like', '%' . $department . '%');
            $filters[] = [
                'param' => 'department',
                'label' => 'Department: ' . $department,
            ];
        }

        $emailVerified = $request->query('email_verified');
        if (in_array($emailVerified, ['true', 'false'], true)) {
            if ($emailVerified === 'true') {
                $query->whereNotNull('sv.email_verified_at');
                $filters[] = [
                    'param' => 'email_verified',
                    'label' => 'Is Email Verified?: True',
                ];
            } else {
                $query->whereNull('sv.email_verified_at');
                $filters[] = [
                    'param' => 'email_verified',
                    'label' => 'Is Email Verified?: False',
                ];
            }
        }

        $verifiedDate = $request->query('email_verified_at');
        if ($verifiedDate && preg_match('/^\d{4}-\d{2}-\d{2}$/', $verifiedDate)) {
            $query->whereDate('sv.email_verified_at', $verifiedDate);
            $filters[] = [
                'param' => 'email_verified_at',
                'label' => 'Email Verified At: ' . $verifiedDate,
            ];
        }

        $hasNotes = $request->query('has_notes');
        if (in_array($hasNotes, ['true', 'false'], true)) {
            if ($hasNotes === 'true') {
                $query->whereNotNull('sv.notes')
                    ->whereRaw("TRIM(sv.notes) <> ''");
                $filters[] = [
                    'param' => 'has_notes',
                    'label' => 'Have Notes?: True',
                ];
            } else {
                $query->where(function ($sub) {
                    $sub->whereNull('sv.notes')
                        ->orWhereRaw("TRIM(sv.notes) = ''");
                });
                $filters[] = [
                    'param' => 'has_notes',
                    'label' => 'Have Notes?: False',
                ];
            }
        }

        $hasPhoto = $request->query('has_photo');
        if (in_array($hasPhoto, ['true', 'false'], true)) {
            if ($hasPhoto === 'true') {
                $query->whereNotNull('sv.photo')
                    ->whereRaw("TRIM(sv.photo) <> ''");
                $filters[] = [
                    'param' => 'has_photo',
                    'label' => 'Have Photo?: True',
                ];
            } else {
                $query->where(function ($sub) {
                    $sub->whereNull('sv.photo')
                        ->orWhereRaw("TRIM(sv.photo) = ''");
                });
                $filters[] = [
                    'param' => 'has_photo',
                    'label' => 'Have Photo?: False',
                ];
            }
        }

        if ($q = trim($request->query('q', ''))) {
            $qLower = strtolower($q);
            $query->where(function ($sub) use ($qLower) {
                foreach (self::SEARCH_COLUMNS as $col) {
                    $sub->orWhereRaw('LOWER(sv.' . $col . ') LIKE ?', ['%' . $qLower . '%']);
                }
            });
        }

        $query->orderBy('sv.name');

        $supervisors = $query->paginate(10)->withQueryString();

        return view('supervisor.index', [
            'supervisors' => $supervisors,
            'filters' => $filters,
            'currentSupervisorId' => $this->currentSupervisorId(),
        ]);
    }

    public function show($school, $id)
    {
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        $supervisor = DB::table('supervisor_details_view')
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();
        abort_if(!$supervisor, 404);
        if (session('role') === 'student') {
            $related = DB::table('monitoring_logs as ml')
                ->join('internships as it', 'ml.internship_id', '=', 'it.id')
                ->where('ml.school_id', $schoolId)
                ->where('ml.supervisor_id', $id)
                ->where('it.school_id', $schoolId)
                ->where('it.student_id', $this->currentStudentId())
                ->exists();
            abort_unless($related, 401);
        } elseif (session('role') === 'supervisor' && $id != $this->currentSupervisorId()) {
            abort(401);
        }
        return view('supervisor.show', compact('supervisor'));
    }

    public function create()
    {
        if (!in_array(session('role'), ['admin', 'developer'])) {
            abort(401);
        }
        
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);
        
        $majors = SchoolMajor::forSchool($schoolId)->active()->orderBy('name')->get();
        
        return view('supervisor.create', [
            'majors' => $majors,
        ]);
    }

    public function store(Request $request)
    {
        if (!in_array(session('role'), ['admin', 'developer'])) {
            abort(401);
        }
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 400, 'School context required.');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
            'supervisor_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('supervisors', 'supervisor_number')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'department_id' => 'required|exists:school_majors,id',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'supervisor',
            'school_id' => $schoolId,
        ]);

        Supervisor::create([
            'user_id' => $user->id,
            'school_id' => $schoolId,
            'supervisor_number' => $data['supervisor_number'],
            'department_id' => $data['department_id'],
            'notes' => $data['notes'] ?? null,
            'photo' => $data['photo'] ?? null,
        ]);

        return redirect($this->schoolRoute('supervisors'))->with('status', 'Supervisor created.');
    }

    public function edit($school, $id)
    {
        if (session('role') === 'student') {
            abort(401);
        }
        if (session('role') === 'supervisor' && $id != $this->currentSupervisorId()) {
            abort(401);
        }
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        $supervisor = DB::table('supervisor_details_view')
            ->where('id', $id)
            ->where('school_id', $schoolId)
            ->first();
        abort_if(!$supervisor, 404);
        
        $majors = SchoolMajor::forSchool($schoolId)->active()->orderBy('name')->get();
        
        return view('supervisor.edit', [
            'supervisor' => $supervisor,
            'majors' => $majors,
        ]);
    }

    public function update(Request $request, $school, $id)
    {
        if (session('role') === 'student') {
            abort(401);
        }
        if (session('role') === 'supervisor' && $id != $this->currentSupervisorId()) {
            abort(401);
        }
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        $supervisor = Supervisor::where('school_id', $schoolId)->findOrFail($id);
        $user = $supervisor->user;

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($user->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'supervisor_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('supervisors', 'supervisor_number')
                    ->ignore($supervisor->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'department_id' => 'required|exists:school_majors,id',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|string|max:255',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $supervisor->update([
            'supervisor_number' => $data['supervisor_number'],
            'department_id' => $data['department_id'],
            'notes' => $data['notes'] ?? null,
            'photo' => $data['photo'] ?? null,
        ]);

        return redirect($this->schoolRoute('supervisors'))->with('status', 'Supervisor updated.');
    }

    public function destroy($school, $id)
    {
        if (session('role') === 'student') {
            abort(401);
        }
        if (session('role') === 'supervisor' && $id != $this->currentSupervisorId()) {
            abort(401);
        }
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        $supervisor = Supervisor::where('school_id', $schoolId)->findOrFail($id);
        $supervisor->user()->delete();
        return redirect($this->schoolRoute('supervisors'))->with('status', 'Supervisor deleted.');
    }
}
