<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Internship;
use App\Models\Supervisor;
use Illuminate\Support\Facades\DB;

class InternshipSupervisorSeeder extends Seeder
{
    public function run(): void
    {
        Internship::with('school')->get()->each(function ($internship) {
            $supervisor = Supervisor::where('school_id', $internship->school_id)->inRandomOrder()->first();
            if (!$supervisor) {
                return;
            }

            DB::table('internship_supervisors')->updateOrInsert(
                [
                    'internship_id' => $internship->id,
                    'supervisor_id' => $supervisor->id,
                ],
                [
                    'is_primary' => true,
                ]
            );
        });
    }
}
