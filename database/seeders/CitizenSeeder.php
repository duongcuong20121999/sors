<?php

namespace Database\Seeders;

use App\Models\Citizen;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitizenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Citizen::create([
            'id' => (string) Str::uuid(),
            'name' => 'Offline',
            'first_name' => '',
            'avatar' => '',
            'address' => '',
            'identity_number' => '000000000000',
            'dob' => now()->subYears(30),
            'dop' => now()->subYears(10),
            'phone_number' => '',
            'created_date' => now(),
            'updated_date' => now(),
            'last_time_login' => now(),
            'zalo_id' => '0000',
        ]);
    }
}
