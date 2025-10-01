<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Supervisor;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingController extends Controller
{
    public function redirect($school)
    {
        return redirect($this->schoolRoute('settings/profile'));
    }

    public function editProfile($school, Request $request)
    {
        $user = $this->resolveUser();
        $role = $user->role;
        $environmentAvailable = $this->environmentAvailable($role);

        $profileDetails = $this->profileDetails($user->id, $role);

        $formData = $this->profileFormData($user, $role);

        return view('settings.profile', [
            'user' => $user,
            'role' => $role,
            'environmentAvailable' => $environmentAvailable,
            'profileDetails' => $profileDetails,
            'formData' => $formData,
        ]);
    }

    public function updateProfile($school, Request $request)
    {
        $user = $this->resolveUser();
        $role = $user->role;

        if ($role === 'student') {
            $this->updateStudentProfile($request, $user);
        } elseif ($role === 'supervisor') {
            $this->updateSupervisorProfile($request, $user);
        } else {
            $this->updateUserProfile($request, $user);
        }

        return redirect($this->schoolRoute('settings/profile'))->with('status', 'Profile updated.');
    }

    public function editSecurity($school)
    {
        $user = $this->resolveUser();
        $role = $user->role;
        $environmentAvailable = $this->environmentAvailable($role);

        $securityDetails = $this->securityDetails($user);

        return view('settings.security', [
            'user' => $user,
            'role' => $role,
            'environmentAvailable' => $environmentAvailable,
            'securityDetails' => $securityDetails,
        ]);
    }

    public function updateSecurity($school, Request $request)
    {
        $user = $this->resolveUser();

        $data = $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8',
            'new_password_confirmation' => 'required|string|same:new_password',
        ]);

        if (!Hash::check($data['old_password'], $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect.']);
        }

        $user->password = Hash::make($data['new_password']);
        $user->save();

        return redirect($this->schoolRoute('settings/security'))->with('status', 'Password updated successfully.');
    }

    public function environments($school)
    {
        $user = $this->resolveUser();
        $role = $user->role;

        abort_unless($this->environmentAvailable($role), 403);

        return view('settings.environments', [
            'role' => $role,
        ]);
    }

    private function resolveUser(): User
    {
        $userId = session('user_id');
        $role = session('role');

        abort_if(!$userId || !$role, 401, 'User session missing.');

        $user = User::find($userId);
        abort_if(!$user, 404, 'User not found.');

        return $user;
    }

    private function environmentAvailable(string $role): bool
    {
        return in_array($role, ['admin', 'developer'], true);
    }

    private function profileDetails(int $userId, string $role): array
    {
        if ($role === 'student') {
            $record = DB::table('student_details_view')->where('user_id', $userId)->first();
            if ($record) {
                return [
                    'Name' => $record->name,
                    'Email' => $record->email,
                    'Phone' => $record->phone ?? 'N/A',
                    'Student Number' => $record->student_number ?? 'N/A',
                    'National SN' => $record->national_sn ?? 'N/A',
                    'Major' => $record->major ?? 'N/A',
                    'Class' => $record->class ?? 'N/A',
                    'Batch' => $record->batch ?? 'N/A',
                ];
            }
        } elseif ($role === 'supervisor') {
            $record = DB::table('supervisor_details_view')->where('user_id', $userId)->first();
            if ($record) {
                return [
                    'Name' => $record->name,
                    'Email' => $record->email,
                    'Phone' => $record->phone ?? 'N/A',
                    'Supervisor Number' => $record->supervisor_number ?? 'N/A',
                    'Department' => $record->department ?? 'N/A',
                ];
            }
        } else {
            $record = DB::table('users')->where('id', $userId)->first();
            if ($record) {
                return [
                    'Name' => $record->name,
                    'Email' => $record->email,
                    'Phone' => $record->phone ?? 'N/A',
                ];
            }
        }

        return [];
    }

    private function profileFormData(User $user, string $role): array
    {
        if ($role === 'student') {
            $student = $this->studentForUser($user->id);
            return [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'student_number' => $student?->student_number,
                'national_sn' => $student?->national_sn,
                'major' => $student?->major,
                'class' => $student?->class,
                'batch' => $student?->batch,
                'notes' => $student?->notes,
                'photo' => $student?->photo,
            ];
        }

        if ($role === 'supervisor') {
            $supervisor = $this->supervisorForUser($user->id);
            return [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'supervisor_number' => $supervisor?->supervisor_number,
                'department' => $supervisor?->department,
                'notes' => $supervisor?->notes,
                'photo' => $supervisor?->photo,
            ];
        }

        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ];
    }

    private function studentForUser(int $userId): ?Student
    {
        $query = Student::where('user_id', $userId);
        if ($schoolId = $this->currentSchoolId()) {
            $query->where('school_id', $schoolId);
        }

        return $query->first();
    }

    private function supervisorForUser(int $userId): ?Supervisor
    {
        $query = Supervisor::where('user_id', $userId);
        if ($schoolId = $this->currentSchoolId()) {
            $query->where('school_id', $schoolId);
        }

        return $query->first();
    }

    private function updateStudentProfile(Request $request, User $user): void
    {
        $student = $this->studentForUser($user->id);
        abort_if(!$student, 404, 'Student profile missing.');

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
            'major' => 'required|string',
            'class' => 'required|string|max:100',
            'batch' => 'required|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        DB::transaction(function () use ($user, $student, $data) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            $user->save();

            $student->update([
                'student_number' => $data['student_number'],
                'national_sn' => $data['national_sn'],
                'major' => $data['major'],
                'class' => $data['class'],
                'batch' => $data['batch'],
                'notes' => $data['notes'] ?? null,
                'photo' => $data['photo'] ?? null,
            ]);
        });
    }

    private function updateSupervisorProfile(Request $request, User $user): void
    {
        $supervisor = $this->supervisorForUser($user->id);
        abort_if(!$supervisor, 404, 'Supervisor profile missing.');

        $schoolId = $supervisor->school_id ?? $this->currentSchoolId();

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
            'supervisor_number' => [
                'required',
                'string',
                Rule::unique('supervisors', 'supervisor_number')
                    ->ignore($supervisor->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ],
            'department' => 'required|string',
            'notes' => 'nullable|string',
            'photo' => 'nullable|string',
        ]);

        DB::transaction(function () use ($user, $supervisor, $data) {
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->phone = $data['phone'] ?? null;
            $user->save();

            $supervisor->update([
                'supervisor_number' => $data['supervisor_number'],
                'department' => $data['department'],
                'notes' => $data['notes'] ?? null,
                'photo' => $data['photo'] ?? null,
            ]);
        });
    }

    private function updateUserProfile(Request $request, User $user): void
    {
        $rules = [
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => 'nullable|string',
        ];

        if ($user->role === 'admin') {
            $schoolId = $user->school_id ?? $this->currentSchoolId();
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($user->id)
                    ->where(fn ($q) => $q->where('school_id', $schoolId)),
            ];
        }

        $data = $request->validate($rules);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'] ?? null;
        $user->save();
    }

    private function securityDetails(User $user): array
    {
        $format = static fn ($value) => $value ? Carbon::parse($value)->format('d M Y H:i') : 'N/A';

        return [
            'Account Role' => ucfirst($user->role),
            'Email Verified' => $user->email_verified_at ? 'Yes (' . $format($user->email_verified_at) . ')' : 'No',
            'Profile Last Updated' => $format($user->updated_at),
            'Account Created' => $format($user->created_at),
        ];
    }
}
