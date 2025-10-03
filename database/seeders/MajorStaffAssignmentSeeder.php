<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolMajor;
use App\Models\Supervisor;
use App\Models\MajorStaffAssignment;

class MajorStaffAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        foreach (School::all() as $school) {
            $majors = SchoolMajor::where('school_id', $school->id)->where('is_active', true)->get();
            $supervisors = Supervisor::where('school_id', $school->id)->get();

            foreach ($majors as $major) {
                // Find supervisors who are assigned to this major/department
                $assignedSupervisors = $supervisors->where('department_id', $major->id);
                
                if ($assignedSupervisors->isNotEmpty()) {
                    // Assign the first supervisor as the major staff
                    $supervisor = $assignedSupervisors->first();
                    
                    MajorStaffAssignment::updateOrCreate(
                        [
                            'school_id' => $school->id,
                            'major_id' => $major->id,
                        ],
                        [
                            'supervisor_id' => $supervisor->id,
                            'major' => $major->name,
                            'major_id' => $major->id,
                        ]
                    );
                }
            }
        }
    }
}
