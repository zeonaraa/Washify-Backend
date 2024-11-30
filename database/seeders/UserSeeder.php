<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'nama' => 'Admin User',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'id_outlet' => 1,
            'role' => 'admin',
        ]);

        User::create([
            'nama' => 'Kasir User',
            'username' => 'kasir',
            'password' => Hash::make('kasir123'),
            'id_outlet' => 1,
            'role' => 'kasir',
        ]);

        User::create([
            'nama' => 'Owner User',
            'username' => 'owner',
            'password' => Hash::make('owner123'),
            'id_outlet' => 1,
            'role' => 'owner',
        ]);
    }
}
