<?php

namespace App\Http\Controllers;

use App\Models\MajorStaffAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class MajorStaffAssignmentController extends Controller
{
    public function index()
    {
        $this->authorizeManage();

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404, 'School context missing.');

        $assignments = DB::table('major_staff_details_view')
            ->where('school_id', $schoolId)
            ->orderBy('major')
            ->paginate(10)
            ->withQueryString();

        return view('major-staff.index', [
            'assignments' => $assignments,
        ]);
    }

    public function create()
    {
        $this->authorizeManage();

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);

        return view('major-staff.create', $this->formData($schoolId));
    }

    public function store(Request $request)
    {
        $this->authorizeManage();

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);

        $validated = $this->validatePayload($request, $schoolId);

        MajorStaffAssignment::create([
            'school_id' => $schoolId,
            'supervisor_id' => $validated['supervisor_id'],
            'major' => $validated['major'],
            'major_slug' => MajorStaffAssignment::slugFromMajor($validated['major']),
        ]);

        return redirect($this->schoolRoute('major-contacts'))->with('status', 'Staff contact assigned successfully.');
    }

    public function edit(int $id)
    {
        $this->authorizeManage();

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);

        $assignment = MajorStaffAssignment::where('school_id', $schoolId)->findOrFail($id);

        return view('major-staff.edit', $this->formData($schoolId, $assignment));
    }

    public function update(Request $request, int $id)
    {
        $this->authorizeManage();

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);

        $assignment = MajorStaffAssignment::where('school_id', $schoolId)->findOrFail($id);
        $validated = $this->validatePayload($request, $schoolId, $assignment->id);

        $assignment->fill([
            'supervisor_id' => $validated['supervisor_id'],
            'major' => $validated['major'],
            'major_slug' => MajorStaffAssignment::slugFromMajor($validated['major']),
        ]);
        $assignment->save();

        return redirect($this->schoolRoute('major-contacts'))->with('status', 'Staff contact updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->authorizeManage();

        $schoolId = $this->currentSchoolId();
        abort_if(!$schoolId, 404);

        $assignment = MajorStaffAssignment::where('school_id', $schoolId)->findOrFail($id);
        $assignment->delete();

        return redirect($this->schoolRoute('major-contacts'))->with('status', 'Staff contact removed successfully.');
    }

    private function formData(int $schoolId, ?MajorStaffAssignment $assignment = null): array
    {
        $supervisors = DB::table('supervisor_details_view')
            ->select('id', 'name', 'email', 'phone', 'department')
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $majors = DB::table('student_details_view')
            ->select('major')
            ->where('school_id', $schoolId)
            ->whereNotNull('major')
            ->whereRaw("TRIM(major) <> ''")
            ->distinct()
            ->orderBy('major')
            ->pluck('major')
            ->all();

        return [
            'assignment' => $assignment,
            'supervisors' => $supervisors,
            'majors' => $majors,
        ];
    }

    private function validatePayload(Request $request, int $schoolId, ?int $assignmentId = null): array
    {
        $validated = $request->validate([
            'major' => ['required', 'string', 'max:150'],
            'supervisor_id' => [
                'required',
                'integer',
                Rule::exists('supervisors', 'id')->where(fn ($query) => $query->where('school_id', $schoolId)),
            ],
        ]);

        $major = trim($validated['major']);
        $slug = MajorStaffAssignment::slugFromMajor($major);

        $duplicate = MajorStaffAssignment::where('school_id', $schoolId)
            ->where('major_slug', $slug)
            ->when($assignmentId, fn ($query) => $query->where('id', '!=', $assignmentId))
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'major' => 'A staff contact already exists for this major.',
            ]);
        }

        return [
            'major' => $major,
            'supervisor_id' => (int) $validated['supervisor_id'],
        ];
    }

    private function authorizeManage(): void
    {
        $role = session('role');
        $allowed = in_array($role, ['developer', 'admin'], true);
        abort_unless($allowed, 403, 'Unauthorized.');
    }
}
