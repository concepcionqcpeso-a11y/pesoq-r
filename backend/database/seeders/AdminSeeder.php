<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder {
    public function run(): void {
        User::create([
            'full_name' => 'PESO Admin',
            'email'     => 'admin@peso.gov.ph',
            'phone'     => '09000000000',
            'password'  => Hash::make('Admin@2024'),
            'is_admin'  => true,
            'is_active' => true,
        ]);
    }
}
