<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Menggunakan updateOrCreate agar tidak eror duplikat, dan teks sesuai ENUM database kamu
        User::updateOrCreate(
            ['email' => 'admin@stockify.com'],
            [
                'name' => 'Muhammad Admin',
                'password' => Hash::make('password123'),
                'role' => 'Admin', 
            ]
        );

        User::updateOrCreate(
            ['email' => 'manager@stockify.com'],
            [
                'name' => 'Ahmad Manajer',
                'password' => Hash::make('password123'),
                'role' => 'Manajer Gudang', 
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@stockify.com'],
            [
                'name' => 'Siti Staff',
                'password' => Hash::make('password123'),
                'role' => 'Staff Gudang', 
            ]
        );
    }
}