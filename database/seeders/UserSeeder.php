<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'nama' => "Admin User $i",
                'username' => "admin$i",
                'password' => Hash::make('admin123'),
                'id_outlet' => 1,
                'role' => 'admin',
            ]);
        }

        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'nama' => "Kasir User $i",
                'username' => "kasir$i",
                'password' => Hash::make('kasir123'),
                'id_outlet' => 1,
                'role' => 'kasir',
            ]);
        }

        for ($i = 1; $i <= 3; $i++) {
            User::create([
                'nama' => "Owner User $i",
                'username' => "owner$i",
                'password' => Hash::make('owner123'),
                'id_outlet' => 1,
                'role' => 'owner',
            ]);
        }
    }
}
