<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Student;
use Carbon\Carbon;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Student::with('school')->get()->each(function ($student, $index) use ($now) {
            $school = $student->school;
            if (!$school) {
                return;
            }

            $institutions = Institution::where('school_id', $school->id)->orderBy('id')->get();
            $periods = Period::where('school_id', $school->id)->orderBy('year')->orderBy('term')->get();

            if ($institutions->isEmpty() || $periods->isEmpty()) {
                return;
            }

            $institution = $institutions[$index % $institutions->count()];
            $period = $periods[$index % $periods->count()];

            Application::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'institution_id' => $institution->id,
                    'period_id' => $period->id,
                ],
                [
                    'school_id' => $school->id,
                    'status' => $index % 3 === 0 ? 'submitted' : 'accepted',
                    'submitted_at' => $now->copy()->subDays($index),
                    'student_access' => true,
                    'notes' => null,
                ]
            );
        });
    }
}
