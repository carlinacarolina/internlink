<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\School;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $institutionsPerSchool = 5;

        foreach (School::all() as $school) {
            $code = Str::upper(Str::substr(Str::slug($school->name, ''), 0, 5));

            for ($i = 1; $i <= $institutionsPerSchool; $i++) {
                Institution::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'name' => sprintf('%s Industry Partner %02d', $code, $i),
                    ],
                    [
                        'address' => $faker->streetAddress,
                        'city' => $faker->city,
                        'province' => $faker->state,
                        'website' => $faker->url,
                        'industry' => $faker->randomElement(['Technology','Manufacturing','Healthcare','Finance','Education']),
                        'notes' => $faker->optional()->sentence,
                        'photo' => $faker->optional()->imageUrl(640, 480, 'business'),
                    ]
                );
            }
        }
    }
}
