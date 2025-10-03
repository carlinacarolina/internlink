<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Institution;
use App\Models\Period;
use App\Models\Student;
use Carbon\Carbon;
use Faker\Factory as Faker;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $now = Carbon::now();

        Student::with('school')->get()->each(function ($student, $index) use ($now, $faker) {
            $school = $student->school;
            if (!$school) {
                return;
            }

            $institutions = Institution::where('school_id', $school->id)->orderBy('id')->get();
            $periods = Period::where('school_id', $school->id)->orderBy('year')->orderBy('term')->get();

            if ($institutions->isEmpty() || $periods->isEmpty()) {
                return;
            }

            // Randomly select institution and period for more variety
            $institution = $institutions->random();
            $period = $periods->random();

            // Generate more realistic status distribution
            $statusOptions = ['submitted', 'under_review', 'accepted', 'rejected'];
            $statusWeights = [0.3, 0.2, 0.4, 0.1]; // More accepted applications
            $status = $faker->randomElements($statusOptions, 1, $statusWeights)[0];

            // Generate realistic dates
            $submittedAt = $now->copy()->subDays($faker->numberBetween(1, 90));
            $plannedStartDate = $submittedAt->copy()->addDays($faker->numberBetween(7, 30));
            $plannedEndDate = $plannedStartDate->copy()->addDays($faker->numberBetween(30, 120));

            Application::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'institution_id' => $institution->id,
                    'period_id' => $period->id,
                ],
                [
                    'school_id' => $school->id,
                    'status' => $status,
                    'submitted_at' => $submittedAt,
                    'planned_start_date' => $plannedStartDate,
                    'planned_end_date' => $plannedEndDate,
                    'student_access' => $faker->boolean(85), // 85% chance of student access
                    'notes' => $faker->optional(0.4)->sentence(12),
                ]
            );
        });
    }
}
