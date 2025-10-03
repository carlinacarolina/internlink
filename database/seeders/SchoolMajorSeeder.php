<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;
use App\Models\SchoolMajor;

class SchoolMajorSeeder extends Seeder
{
    public function run(): void
    {
        $majorsBySchool = [
            'ALPHAVOC' => [
                'Teknik Informatika',
                'Teknik Komputer',
                'Sistem Informasi',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Sipil',
                'Administrasi Bisnis',
                'Akuntansi',
                'Manajemen',
                'Desain Komunikasi Visual',
                'Teknik Otomotif',
                'Teknik Kimia'
            ],
            'BETATECH' => [
                'Teknik Informatika',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Sipil',
                'Teknik Industri',
                'Teknik Lingkungan',
                'Teknik Geologi',
                'Teknik Pertambangan',
                'Teknik Perminyakan',
                'Teknik Nuklir',
                'Teknik Material',
                'Teknik Biomedis'
            ],
            'GAMMAIND' => [
                'Teknik Informatika',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Industri',
                'Teknik Kimia',
                'Teknik Lingkungan',
                'Teknik Material',
                'Teknik Fisika',
                'Teknik Geodesi',
                'Teknik Geologi',
                'Teknik Pertambangan',
                'Teknik Perminyakan',
                'Teknik Nuklir',
                'Teknik Biomedis',
                'Teknik Penerbangan',
                'Teknik Kelautan'
            ],
            'DELTAENG' => [
                'Teknik Informatika',
                'Teknik Elektro',
                'Teknik Mesin',
                'Teknik Sipil',
                'Teknik Industri',
                'Teknik Lingkungan',
                'Teknik Kimia',
                'Teknik Material',
                'Teknik Geodesi',
                'Teknik Geologi',
                'Teknik Pertambangan',
                'Teknik Perminyakan'
            ],
            'EPSILON' => [
                'Teknik Informatika',
                'Sistem Informasi',
                'Teknik Komputer',
                'Desain Komunikasi Visual',
                'Multimedia',
                'Animasi',
                'Game Development',
                'Digital Marketing',
                'Teknik Elektro',
                'Teknik Mesin',
                'Administrasi Bisnis',
                'Akuntansi'
            ]
        ];

        foreach (School::all() as $school) {
            $schoolCode = $school->code;
            $majors = $majorsBySchool[$schoolCode] ?? [];

            foreach ($majors as $majorName) {
                SchoolMajor::updateOrCreate(
                    [
                        'school_id' => $school->id,
                        'name' => $majorName,
                    ],
                    [
                        'slug' => \Illuminate\Support\Str::slug($majorName),
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
