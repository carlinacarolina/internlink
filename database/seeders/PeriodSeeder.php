<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Period;
use App\Models\School;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $years = [2024, 2025, 2026];

        foreach (School::all() as $school) {
            foreach ($years as $year) {
                foreach ([1, 2] as $term) {
                    Period::updateOrCreate(
                        [
                            'school_id' => $school->id,
                            'year' => $year,
                            'term' => $term,
                        ],
                        []
                    );
                }
            }
        }
    }
}
