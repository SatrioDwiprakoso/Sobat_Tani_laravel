<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Membuat akun Admin Default
        Pengguna::updateOrCreate(
            ['email' => 'admin@sobattani.com'],
            [
                'nama_lengkap' => 'Administrator',
                'kata_sandi' => Hash::make('password123'),
                'peran' => 'admin'
            ]
        );
    }
}