<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus user leader lama jika ada
        User::where('email', 'leader@engineering.com')->delete();

        $users = [
            [
                'email' => 'admin@engineering.com',
                'name' => 'Admin Engineering',
                'role' => 'admin',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'karyawan@engineering.com',
                'name' => 'Karyawan Engineering',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'adit@engineering.com',
                'name' => 'Adit (Karyawan)',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'hendra@engineering.com',
                'name' => 'Hendra (Karyawan)',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'azlul@engineering.com',
                'name' => 'Azlul (Karyawan)',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'andri@engineering.com',
                'name' => 'Andri (Karyawan)',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'kukuh@engineering.com',
                'name' => 'Kukuh (Karyawan)',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'iwan@engineering.com',
                'name' => 'Iwan (Karyawan)',
                'role' => 'karyawan',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'rossie@engineering.com',
                'name' => 'Rossie (Leader)',
                'role' => 'leader',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'misdan@engineering.com',
                'name' => 'Misdan (Leader)',
                'role' => 'leader',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'opik@engineering.com',
                'name' => 'Opik (Leader)',
                'role' => 'leader',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'manager@engineering.com',
                'name' => 'Dimas Farid Awaludin, S.Kom (Manager)',
                'role' => 'manager',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'accounting@engineering.com',
                'name' => 'Baiq Nana Erlina, A.Md (Accounting)',
                'role' => 'accounting',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'direktur@engineering.com',
                'name' => 'Galuh Zakiyatun, S.Kom (Direktur)',
                'role' => 'direktur',
                'password' => Hash::make('password123'),
            ],
            [
                'email' => 'penasihat@engineering.com',
                'name' => 'Raden Yuniarta Alba, S.Kom (Penasihat)',
                'role' => 'penasihat',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}

