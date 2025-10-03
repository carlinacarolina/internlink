<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\School;
use App\Models\SchoolMajor;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class InstitutionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        
        // More diverse and realistic Indonesian companies
        $companyTemplates = [
            'PT Teknologi Maju Indonesia',
            'CV Industri Kreatif Nusantara',
            'PT Solusi Digital Indonesia',
            'UD Mandiri Engineering',
            'PT Global Manufacturing',
            'CV Inovasi Teknologi',
            'PT Sistem Informasi Terpadu',
            'UD Jaya Konstruksi',
            'PT Media Digital Indonesia',
            'CV Teknik Modern',
            'PT Konsultan Bisnis Indonesia',
            'UD Sumber Daya Alam',
            'PT Teknologi Hijau',
            'CV Desain Kreatif',
            'PT Logistik Indonesia',
            'UD Teknik Presisi',
            'PT Sistem Otomasi',
            'CV Teknologi Masa Depan',
            'PT Inovasi Digital',
            'UD Teknik Terdepan'
        ];

        // Industries will be replaced by school majors

        $cities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang',
            'Makassar', 'Palembang', 'Tangerang', 'Depok', 'Bekasi',
            'Yogyakarta', 'Malang', 'Denpasar', 'Balikpapan', 'Padang',
            'Pekanbaru', 'Bandar Lampung', 'Banjarmasin', 'Pontianak', 'Manado'
        ];

        $provinces = [
            'DKI Jakarta', 'Jawa Timur', 'Jawa Barat', 'Sumatera Utara', 'Jawa Tengah',
            'Sulawesi Selatan', 'Sumatera Selatan', 'Banten', 'Bali', 'Kalimantan Timur',
            'DI Yogyakarta', 'Jawa Timur', 'Bali', 'Kalimantan Timur', 'Sumatera Barat',
            'Riau', 'Lampung', 'Kalimantan Selatan', 'Kalimantan Barat', 'Sulawesi Utara'
        ];

        foreach (School::all() as $school) {
            $institutionsPerSchool = $faker->numberBetween(8, 15);
            $usedCompanies = [];
            
            // Get school majors for this school
            $schoolMajors = SchoolMajor::where('school_id', $school->id)
                ->where('is_active', true)
                ->get();

            for ($i = 1; $i <= $institutionsPerSchool; $i++) {
                // Select unique company name
                do {
                    $companyName = $faker->randomElement($companyTemplates);
                } while (in_array($companyName, $usedCompanies));
                $usedCompanies[] = $companyName;

                $city = $faker->randomElement($cities);
                $province = $faker->randomElement($provinces);
                
                // Select a random school major for this institution
                $selectedMajor = $schoolMajors->isNotEmpty() 
                    ? $schoolMajors->random() 
                    : null;

                Institution::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'name' => $companyName,
                    ],
                    [
                        'address' => sprintf('Jl. %s No. %d, %s', 
                            $faker->randomElement(['Sudirman', 'Thamrin', 'Gatot Subroto', 'HR Rasuna Said', 'Menteng Raya', 'Kemang Raya', 'Senayan', 'Kuningan']),
                            $faker->numberBetween(1, 999),
                            $city
                        ),
                        'city' => $city,
                        'province' => $province,
                        'website' => sprintf('https://%s.com', Str::slug($companyName)),
                        'industry_for' => $selectedMajor?->id,
                        'notes' => $faker->optional(0.3)->sentence(10),
                        'photo' => $faker->optional(0.1)->imageUrl(640, 480, 'business'),
                    ]
                );
            }
        }
    }
}
