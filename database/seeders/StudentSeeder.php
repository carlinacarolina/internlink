<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $studentsBySchool = User::where('role', 'student')
            ->with('school')
            ->get()
            ->groupBy('school_id');

        foreach ($studentsBySchool as $schoolId => $users) {
            $school = School::find($schoolId);
            if (!$school) {
                continue;
            }

            $code = Str::upper(Str::substr(Str::slug($school->name, ''), 0, 5));

            foreach ($users as $user) {
                $sequence = str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $school->id,
                        'student_number' => sprintf('STU-%s-%s', $code, $sequence),
                        'national_sn' => sprintf('NSN-%s-%s', $code, $sequence),
                        'major' => $faker->randomElement(['Computer Science', 'Information Systems', 'Engineering', 'Business']),
                        'class' => $faker->bothify('XII-??'),
                        'batch' => (string) $faker->numberBetween(2020, 2025),
                        'notes' => $faker->optional()->sentence,
                        'photo' => $faker->optional()->imageUrl(400, 300, 'people'),
                    ]
                );
            }
        }
    }
}
