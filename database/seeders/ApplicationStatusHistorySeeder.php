<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Application;
use Illuminate\Support\Facades\DB;

class ApplicationStatusHistorySeeder extends Seeder
{
    public function run(): void
    {
        Application::all()->each(function ($application) {
            DB::table('application_status_history')->updateOrInsert(
                [
                    'application_id' => $application->id,
                    'to_status' => $application->status,
                ],
                [
                    'school_id' => $application->school_id,
                    'from_status' => 'submitted',
                ]
            );
        });
    }
}
