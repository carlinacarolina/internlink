<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internship;
use App\Models\MonitoringLog;
use App\Models\Supervisor;
use Carbon\Carbon;
use Faker\Factory as Faker;

class MonitoringLogSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        Internship::with('school')->get()->each(function ($internship, $index) use ($faker) {
            $supervisor = Supervisor::where('school_id', $internship->school_id)->inRandomOrder()->first();

            MonitoringLog::updateOrCreate(
                [
                    'internship_id' => $internship->id,
                    'log_date' => Carbon::now()->subDays($index)->toDateString(),
                ],
                [
                    'school_id' => $internship->school_id,
                    'supervisor_id' => optional($supervisor)->id,
                    'title' => 'Weekly Report ' . ($index + 1),
                    'content' => $faker->paragraph,
                    'type' => 'weekly',
                ]
            );
        });
    }
}
