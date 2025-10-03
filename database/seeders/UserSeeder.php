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
        $studentPerSchool = $faker->numberBetween(15, 25);
        $supervisorPerSchool = $faker->numberBetween(8, 12);

        // Indonesian names for more realistic data
        $maleNames = [
            'Ahmad Wijaya', 'Budi Santoso', 'Cahyo Nugroho', 'Dedi Prasetyo', 'Eko Susilo',
            'Fajar Rahman', 'Gunawan Sari', 'Hadi Kusuma', 'Iwan Setiawan', 'Joko Widodo',
            'Kurniawan', 'Lukman Hakim', 'Muhammad Ali', 'Nur Hidayat', 'Omar Syarif',
            'Prasetyo', 'Qori Sandria', 'Rizki Pratama', 'Surya Dharma', 'Taufik Hidayat',
            'Umar Said', 'Viktor Surya', 'Wahyu Nugroho', 'Yusuf Kurniawan', 'Zainal Abidin'
        ];

        $femaleNames = [
            'Ayu Lestari', 'Bunga Sari', 'Citra Dewi', 'Dewi Kartika', 'Eka Sari',
            'Fitriani', 'Gita Maharani', 'Hani Susanti', 'Indira Putri', 'Jihan Sari',
            'Kartika Dewi', 'Lestari', 'Maya Sari', 'Nurul Hikmah', 'Oktavia',
            'Putri Ayu', 'Qonita', 'Rina Sari', 'Sari Dewi', 'Tika Maharani',
            'Umi Kulsum', 'Vina Sari', 'Wulan Sari', 'Yuni Sari', 'Zahra Putri'
        ];

        foreach (School::all() as $school) {
            $slug = Str::slug($school->name, '');

            for ($i = 1; $i <= $studentPerSchool; $i++) {
                $isMale = $faker->boolean();
                $name = $isMale ? $faker->randomElement($maleNames) : $faker->randomElement($femaleNames);
                
                User::updateOrCreate(
                    ['email' => sprintf('student%02d@%s.sch.test', $i, $slug)],
                    [
                        'name' => $name,
                        'phone' => $faker->numerify('0812#######'),
                        'password' => Hash::make('password'),
                        'role' => 'student',
                        'school_id' => $school->id,
                    ]
                );
            }

            for ($i = 1; $i <= $supervisorPerSchool; $i++) {
                $isMale = $faker->boolean();
                $name = $isMale ? $faker->randomElement($maleNames) : $faker->randomElement($femaleNames);
                
                User::updateOrCreate(
                    ['email' => sprintf('supervisor%02d@%s.sch.test', $i, $slug)],
                    [
                        'name' => $name,
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
