<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $schools = [
            [
                'code' => 'ALPHAVOC',
                'name' => 'Alpha Vocational School',
                'address' => '123 Alpha Street, Jakarta',
                'phone' => '+62-21-1234-5678',
                'email' => 'contact@alpha-voc.sch.id',
                'website' => 'https://alpha-voc.sch.id',
            ],
            [
                'code' => 'BETATECH',
                'name' => 'Beta Technical Institute',
                'address' => '45 Beta Avenue, Bandung',
                'phone' => '+62-22-9876-5432',
                'email' => 'hello@beta-tech.sch.id',
                'website' => 'https://beta-tech.sch.id',
            ],
            [
                'code' => 'GAMMAIND',
                'name' => 'Gamma Industrial Academy',
                'address' => '9 Gamma Boulevard, Surabaya',
                'phone' => '+62-31-7654-3210',
                'email' => 'info@gamma-industrial.sch.id',
                'website' => 'https://gamma-industrial.sch.id',
            ],
        ];

        foreach ($schools as $attributes) {
            School::updateOrCreate(
                ['code' => $attributes['code']],
                $attributes
            );
        }
    }
}
