<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'hrd@cherastudio.test'],
            [
                'name' => 'HRD Chera Studio',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ]
        );
    }
}