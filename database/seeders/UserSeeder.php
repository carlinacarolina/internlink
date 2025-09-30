<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $studentPerSchool = 12;
        $supervisorPerSchool = 6;

        foreach (School::all() as $school) {
            $slug = Str::slug($school->name, '');

            for ($i = 1; $i <= $studentPerSchool; $i++) {
                User::updateOrCreate(
                    ['email' => sprintf('student%02d@%s.sch.test', $i, $slug)],
                    [
                        'name' => $faker->name,
                        'phone' => $faker->numerify('0812#######'),
                        'password' => Hash::make('password'),
                        'role' => 'student',
                        'school_id' => $school->id,
                    ]
                );
            }

            for ($i = 1; $i <= $supervisorPerSchool; $i++) {
                User::updateOrCreate(
                    ['email' => sprintf('supervisor%02d@%s.sch.test', $i, $slug)],
                    [
                        'name' => $faker->name,
                        'phone' => $faker->numerify('0821#######'),
                        'password' => Hash::make('password'),
                        'role' => 'supervisor',
                        'school_id' => $school->id,
                    ]
                );
            }
        }
    }
}
