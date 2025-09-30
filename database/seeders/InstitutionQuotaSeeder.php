<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institution;
use App\Models\InstitutionQuota;
use App\Models\Period;

class InstitutionQuotaSeeder extends Seeder
{
    public function run(): void
    {
        Institution::with('school')->get()->each(function ($institution) {
            $periods = Period::where('school_id', $institution->school_id)->get();

            foreach ($periods as $period) {
                InstitutionQuota::updateOrCreate(
                    [
                        'institution_id' => $institution->id,
                        'period_id' => $period->id,
                    ],
                    [
                        'school_id' => $institution->school_id,
                        'quota' => random_int(5, 15),
                        'used' => 0,
                    ]
                );
            }
        });
    }
}
