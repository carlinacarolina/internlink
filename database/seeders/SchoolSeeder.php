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
                'name' => 'SMK Alpha Vocational',
                'address' => 'Jl. Pendidikan No. 123, Kelurahan Menteng, Kecamatan Menteng',
                'city' => 'Jakarta Pusat',
                'postal_code' => '10310',
                'phone' => '+62-21-1234-5678',
                'email' => 'kontak@alpha-voc.sch.id',
                'website' => 'https://alpha-voc.sch.id',
                'principal_name' => 'Dr. Ahmad Wijaya, S.Pd., M.Pd.',
                'principal_nip' => '196512151990031001',
            ],
            [
                'code' => 'BETATECH',
                'name' => 'SMK Beta Technology',
                'address' => 'Jl. Teknologi No. 45, Kelurahan Dago, Kecamatan Coblong',
                'city' => 'Bandung',
                'postal_code' => '40135',
                'phone' => '+62-22-9876-5432',
                'email' => 'info@beta-tech.sch.id',
                'website' => 'https://beta-tech.sch.id',
                'principal_name' => 'Ir. Siti Nurhaliza, M.T.',
                'principal_nip' => '197203201995122001',
            ],
            [
                'code' => 'GAMMAIND',
                'name' => 'SMK Gamma Industrial',
                'address' => 'Jl. Industri No. 9, Kelurahan Wonokromo, Kecamatan Wonokromo',
                'city' => 'Surabaya',
                'postal_code' => '60243',
                'phone' => '+62-31-7654-3210',
                'email' => 'admin@gamma-industrial.sch.id',
                'website' => 'https://gamma-industrial.sch.id',
                'principal_name' => 'Drs. Bambang Sutrisno, M.M.',
                'principal_nip' => '196808151990031002',
            ],
            [
                'code' => 'DELTAENG',
                'name' => 'SMK Delta Engineering',
                'address' => 'Jl. Rekayasa No. 78, Kelurahan Semarang Selatan, Kecamatan Semarang Selatan',
                'city' => 'Semarang',
                'postal_code' => '50244',
                'phone' => '+62-24-5555-7777',
                'email' => 'sekretariat@delta-eng.sch.id',
                'website' => 'https://delta-eng.sch.id',
                'principal_name' => 'Ir. Dewi Kartika, S.T., M.Eng.',
                'principal_nip' => '197512101998032001',
            ],
            [
                'code' => 'EPSILON',
                'name' => 'SMK Epsilon Digital',
                'address' => 'Jl. Digital No. 12, Kelurahan Denpasar Selatan, Kecamatan Denpasar Selatan',
                'city' => 'Denpasar',
                'postal_code' => '80225',
                'phone' => '+62-361-8888-9999',
                'email' => 'humas@epsilon-digital.sch.id',
                'website' => 'https://epsilon-digital.sch.id',
                'principal_name' => 'Dra. Made Sari, M.Kom.',
                'principal_nip' => '197911201999032001',
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
