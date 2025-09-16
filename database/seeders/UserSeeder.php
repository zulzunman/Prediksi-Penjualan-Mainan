<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder database.
     */
    public function run(): void
    {
        // Admin Toko
        User::create([
            'name' => 'Admin Toko',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'), // ganti sesuai kebutuhan
            'role' => 'admin',
        ]);

        // Pemilik Toko
        User::create([
            'name' => 'Pemilik Toko',
            'email' => 'pemilik@toko.com',
            'password' => Hash::make('password123'), // ganti sesuai kebutuhan
            'role' => 'pemilik',
        ]);
    }
}
