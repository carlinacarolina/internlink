<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
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

            foreach ($users as $user) {
                $sequence = str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
                Supervisor::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $school->id,
                        'supervisor_number' => sprintf('SPV-%s-%s', $code, $sequence),
                        'department' => $faker->randomElement(['Engineering', 'Business', 'Design', 'Operations']),
                        'notes' => $faker->optional()->sentence,
                        'photo' => $faker->optional()->imageUrl(400, 300, 'people'),
                    ]
                );
            }
        }
    }
}
