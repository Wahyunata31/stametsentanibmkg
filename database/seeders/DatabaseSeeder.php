<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan baris ini di bagian atas

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menghapus penggunaan factory default agar password terjamin menggunakan Bcrypt
        User::create([
            'name' => 'admin',                       // <-- Ini Username kamu nanti
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),    // <-- Ini Password kamu nanti
        ]);
    }
}