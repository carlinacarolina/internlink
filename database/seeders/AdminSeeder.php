<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\School;
use App\Models\User;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        foreach (School::all() as $school) {
            $slug = Str::slug($school->name, '');

            User::updateOrCreate(
                ['email' => sprintf('admin@%s.sch.test', $slug)],
                [
                    'name' => $school->name . ' Admin',
                    'phone' => '080' . random_int(10000000, 99999999),
                    'password' => Hash::make('password'),
                    'role' => 'admin',
                    'school_id' => $school->id,
                ]
            );
        }
    }
}
