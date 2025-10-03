<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolMajor;
use App\Models\Supervisor;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class SupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $supervisorsBySchool = User::where('role', 'supervisor')
            ->with('school')
            ->get()
            ->groupBy('school_id');

        foreach ($supervisorsBySchool as $schoolId => $users) {
            $school = School::find($schoolId);
            if (!$school) {
                continue;
            }

            $code = Str::upper(Str::substr(Str::slug($school->name, ''), 0, 5));
            $majors = SchoolMajor::where('school_id', $school->id)->where('is_active', true)->get();

            foreach ($users as $user) {
                $sequence = str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
                
                // Randomly assign a department/major from the school's available majors
                $major = $majors->isNotEmpty() ? $majors->random() : null;
                
                Supervisor::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $school->id,
                        'supervisor_number' => sprintf('SPV-%s-%s', $code, $sequence),
                        'department_id' => $major ? $major->id : null,
                        'notes' => $faker->optional(0.4)->sentence(8),
                        'photo' => $faker->optional(0.15)->imageUrl(400, 300, 'people'),
                    ]
                );
            }
        }
    }
}
