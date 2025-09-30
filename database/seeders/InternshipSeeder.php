<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\Internship;
use Carbon\Carbon;

class InternshipSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        Application::where('status', 'accepted')
            ->with(['student', 'institution'])
            ->get()
            ->each(function ($application) use ($now) {
                $start = $now->copy()->subWeeks(random_int(0, 4));

                Internship::updateOrCreate(
                    ['application_id' => $application->id],
                    [
                        'school_id' => $application->school_id,
                        'student_id' => $application->student_id,
                        'institution_id' => $application->institution_id,
                        'period_id' => $application->period_id,
                        'start_date' => $start->toDateString(),
                        'end_date' => $start->copy()->addMonths(3)->toDateString(),
                        'status' => 'ongoing',
                    ]
                );
            });
    }
}
