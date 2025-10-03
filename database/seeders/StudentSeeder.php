<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolMajor;
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
            $majors = SchoolMajor::where('school_id', $school->id)->where('is_active', true)->get();

            foreach ($users as $user) {
                $sequence = str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);
                
                // Randomly assign a major from the school's available majors
                $major = $majors->isNotEmpty() ? $majors->random() : null;
                
                // Generate more realistic Indonesian names and data
                $batch = $faker->numberBetween(2020, 2025);
                $classOptions = ['X', 'XI', 'XII'];
                $class = $faker->randomElement($classOptions);
                $classNumber = $faker->numberBetween(1, 6);
                
                Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $school->id,
                        'student_number' => sprintf('STU-%s-%s', $code, $sequence),
                        'national_sn' => sprintf('NSN-%s-%s', $code, $sequence),
                        'major_id' => $major ? $major->id : null,
                        'class' => sprintf('%s-%d', $class, $classNumber),
                        'batch' => (string) $batch,
                        'notes' => $faker->optional(0.3)->sentence(6),
                        'photo' => $faker->optional(0.2)->imageUrl(400, 300, 'people'),
                    ]
                );
            }
        }
    }
}
