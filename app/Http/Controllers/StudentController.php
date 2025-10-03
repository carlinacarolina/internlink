<?php

namespace App\Http\Controllers;

use App\Models\SchoolMajor;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    private const SEARCH_COLUMNS = [
        'name',
        'email',
        'phone',
        'student_number',
        'national_sn',
        'major',
        'class',
        'batch',
    ];

    private const DISPLAY_COLUMNS = [
        'id',
        'name',
        'email',
        'phone',
        'student_number',
        'national_sn',
        'major',
        'class',
        'batch',
    ];

    private const EXTRA_COLUMNS = ['notes', 'photo', 'email_verified_at', 'created_at', 'updated_at'];

    public function index(Request $request)
    {
        $query = DB::table('student_details_view')
            ->select(array_merge(self::DISPLAY_COLUMNS, self::EXTRA_COLUMNS));

        if ($school = $this->currentSchool()) {
            $query->where('school_id', $school->id);
        }

        if (session('role') === 'student') {
            $query->where('id', $this->currentStudentId());
        }

        $filters = [];

        $name = trim($request->query('name', ''));
        if ($name !== '') {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($name) . '%']);
            $filters['name'] = 'Name: ' . $name;
        }

        $email = trim($request->query('email', ''));
        if ($email !== '') {
            $query->whereRaw('LOWER(email) LIKE ?', ['%' . strtolower($email) . '%']);
            $filters['email'] = 'Email: ' . $email;
        }

        $phone = trim($request->query('phone', ''));
        if ($phone !== '') {
            $query->whereRaw("LOWER(COALESCE(phone, '')) LIKE ?", ['%' . strtolower($phone) . '%']);
            $filters['phone'] = 'Phone: ' . $phone;
        }

        $emailVerified = $request->query('email_verified');
        if (in_array($emailVerified, ['true', 'false'], true)) {
            if ($emailVerified === 'true') {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
            $filters['email_verified'] = 'Is Email Verified?: ' . ucfirst($emailVerified);
        }

        $emailVerifiedAt = $request->query('email_verified_at');
        if ($emailVerifiedAt) {
            $query->whereDate('email_verified_at', $emailVerifiedAt);
            $filters['email_verified_at'] = 'Email Verified At: ' . $emailVerifiedAt;
        }

        $studentNumber = trim($request->query('student_number', ''));
        if ($studentNumber !== '') {
            $query->whereRaw('LOWER(student_number) LIKE ?', ['%' . strtolower($studentNumber) . '%']);
            $filters['student_number'] = 'Student Number: ' . $studentNumber;
        }

        $nationalSn = trim($request->query('national_sn', ''));
        if ($nationalSn !== '') {
            $query->whereRaw('LOWER(national_sn) LIKE ?', ['%' . strtolower($nationalSn) . '%']);
            $filters['national_sn'] = 'National Student Number: ' . $nationalSn;
        }

        $major = trim($request->query('major', ''));
        if ($major !== '') {
            $query->whereRaw('LOWER(major) LIKE ?', ['%' . strtolower($major) . '%']);
            $filters['major'] = 'Major: ' . $major;
        }

        $class = trim($request->query('class', ''));
        if ($class !== '') {
            $query->whereRaw('LOWER(class) LIKE ?', ['%' . strtolower($class) . '%']);
            $filters['class'] = 'Class: ' . $class;
        }

        $batch = trim($request->query('batch', ''));
        if ($batch !== '') {
            $query->where('batch', $batch);
            $filters['batch'] = 'Batch: ' . $batch;
        }

        $hasNotes = $request->query('has_notes');
        if (in_array($hasNotes, ['true', 'false'], true)) {
            if ($hasNotes === 'true') {
                $query->whereNotNull('notes')->whereRaw("TRIM(notes) <> ''");
            } else {
                $query->where(function ($sub) {
                    $sub->whereNull('notes')->orWhereRaw("TRIM(COALESCE(notes, '')) = ''");
                });
            }
            $filters['has_notes'] = 'Has Notes?: ' . ucfirst($hasNotes);
        }

        $hasPhoto = $request->query('has_photo');
        if (in_array($hasPhoto, ['true', 'false'], true)) {
            if ($hasPhoto === 'true') {
                $query->whereNotNull('photo')->whereRaw("TRIM(photo) <> ''");
            } else {
                $query->where(function ($sub) {
                    $sub->whereNull('photo')->orWhereRaw("TRIM(COALESCE(photo, '')) = ''");
                });
            }
            $filters['has_photo'] = 'Has Photo?: ' . ucfirst($hasPhoto);
        }

        if ($q = trim($request->query('q', ''))) {
            $qLower = strtolower($q);
            $query->where(function ($sub) use ($qLower) {
                foreach (self::SEARCH_COLUMNS as $col) {
                    $sub->orWhereRaw('LOWER(COALESCE(' . $col . ", '')) LIKE ?", ['%' . $qLower . '%']);
                }
            });
        }

        $students = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('student.index', [
            'students' => $students,
            'filters' => $filters,
        ]);
    }

    public function show($school, $id)
    {
        $studentQuery = DB::table('student_details_view')->where('id', $id);
        if ($school = $this->currentSchool()) {
            $studentQuery->where('school_id', $school->id);
        }
        $student = $studentQuery->first();
        abort_if(!$student, 404);

        if (session('role') === 'student' && $student->id !== $this->currentStudentId()) {
            abort(401);
        }

        return view('student.show', compact('student'));
    }

    public function create()
    {
        if (session('role') === 'student') {
            abort(401);
        }
        
        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);
        
        $majors = SchoolMajor::forSchool($schoolId)->active()->orderBy('name')->get();
        
        return view('student.create', [
            'majors' => $majors,
        ]);
    }

    public function store(Request $request)
    {
        if (session('role') === 'student') {
            abort(401);
        }

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 400, 'School context required.');

        $data = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'phone' => 'nullable|string',
            'password' => 'required|string',
            'student_number' => [
                'required',
                'string',
                Rule::unique('students', 'student_number')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'national_sn' => [
                'required',
                'string',
                Rule::unique('students', 'national_sn')->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'major_id' => 'required|exists:school_majors,id',
            'class' => 'required|string|max:100',
            'batch' => 'required|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => 'student',
            'school_id' => $schoolId,
        ]);

        Student::create([
            'user_id' => $user->id,
            'school_id' => $schoolId,
            'student_number' => $data['student_number'],
            'national_sn' => $data['national_sn'],
            'major_id' => $data['major_id'],
            'class' => $data['class'],
            'batch' => $data['batch'],
            'notes' => $data['notes'] ?? null,
            'photo' => $data['photo'] ?? null,
        ]);

        return redirect($this->schoolRoute('students'))->with('status', 'Student created.');
    }

    public function edit($school, $id)
    {
        if (session('role') === 'student' && (int) $id !== $this->currentStudentId()) {
            abort(401);
        }
        $studentQuery = DB::table('student_details_view')->where('id', $id);
        if ($school = $this->currentSchool()) {
            $studentQuery->where('school_id', $school->id);
        }
        $student = $studentQuery->first();
        abort_if(!$student, 404);

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);
        
        $majors = SchoolMajor::forSchool($schoolId)->active()->orderBy('name')->get();

        return view('student.edit', [
            'student' => $student,
            'majors' => $majors,
        ]);
    }

    public function update(Request $request, $school, $id)
    {
        $studentQuery = Student::with('user')->where('id', $id);
        if ($schoolId = $this->currentSchoolId()) {
            $studentQuery->where('school_id', $schoolId);
        }
        $student = $studentQuery->firstOrFail();

        if (session('role') === 'student' && $student->user_id !== session('user_id')) {
            abort(401);
        }

        $user = $student->user;
        $schoolId = $student->school_id ?? $this->currentSchoolId();

        $data = $request->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($user->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'phone' => 'nullable|string',
            'password' => 'nullable|string',
            'student_number' => [
                'required',
                'string',
                Rule::unique('students', 'student_number')
                    ->ignore($student->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'national_sn' => [
                'required',
                'string',
                Rule::unique('students', 'national_sn')
                    ->ignore($student->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'major_id' => 'required|exists:school_majors,id',
            'class' => 'required|string|max:100',
            'batch' => 'required|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        $student->update([
            'student_number' => $data['student_number'],
            'national_sn' => $data['national_sn'],
            'major_id' => $data['major_id'],
            'class' => $data['class'],
            'batch' => $data['batch'],
            'notes' => $data['notes'] ?? null,
            'photo' => $data['photo'] ?? null,
        ]);

        $message = session('role') === 'student'
            ? 'Profile updated.'
            : 'Student updated.';

        return redirect($this->schoolRoute('students'))->with('status', $message);
    }

    public function destroy(Request $request, $school, $id)
    {
        $studentQuery = Student::with('user')->where('id', $id);
        if ($schoolId = $this->currentSchoolId()) {
            $studentQuery->where('school_id', $schoolId);
        }
        $student = $studentQuery->firstOrFail();
        if (session('role') === 'student' && $student->user_id !== session('user_id')) {
            abort(401);
        }

        $student->user()->delete();

        if (session('user_id') === $student->user_id) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('status', 'Your account has been deleted. You have been logged out.');
        }

        return redirect($this->schoolRoute('students'))->with('status', 'Student deleted.');
    }
}
