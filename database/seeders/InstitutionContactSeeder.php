<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\InstitutionContact;
use Faker\Factory as Faker;

class InstitutionContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        Institution::all()->each(function ($institution) use ($faker) {
            $email = sprintf('contact-%d@%s.test', $institution->id, $faker->domainWord);

            InstitutionContact::updateOrCreate(
                [
                    'institution_id' => $institution->id,
                    'email' => $email,
                ],
                [
                    'name' => $faker->name,
                    'phone' => $faker->phoneNumber,
                    'position' => $faker->jobTitle,
                    'is_primary' => true,
                ]
            );
        });
    }
}
