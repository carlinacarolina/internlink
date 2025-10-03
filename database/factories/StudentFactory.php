<?php

namespace Database\Factories;

use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'school_id' => School::factory(),
            'user_id' => null,
            'student_number' => $this->faker->unique()->bothify('S-####'),
            'national_sn' => $this->faker->unique()->bothify('NSN-####'),
            'major' => $this->faker->randomElement(['Computer Science', 'Information Systems', 'Engineering']),
            'class' => $this->faker->bothify('XI-?? #'),
            'batch' => (string) $this->faker->year(),
            'notes' => null,
            'photo' => null,
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Student $student) {
            if (!$student->school_id) {
                $student->school_id = School::factory()->create()->id;
            }

            if (!$student->user_id) {
                $student->user_id = User::factory()->create([
                    'role' => 'student',
                    'school_id' => $student->school_id,
                ])->id;
            } else {
                $user = $student->user ?? User::find($student->user_id);
                if ($user) {
                    $user->forceFill([
                        'role' => 'student',
                        'school_id' => $student->school_id,
                    ])->save();
                }
            }
        })->afterCreating(function (Student $student) {
            $student->user()?->update([
                'role' => 'student',
                'school_id' => $student->school_id,
            ]);
        });
    }
}
