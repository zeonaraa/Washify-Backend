<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;

class OutletSeeder extends Seeder
{
    public function run()
    {
        Outlet::create([
            'nama' => 'Laundry Bersih',
            'alamat' => 'Jl. Kebersihan No. 1',
            'tlp' => '081234567890',
        ]);

        Outlet::create([
            'nama' => 'Laundry Cepat',
            'alamat' => 'Jl. Cepat Kilat No. 2',
            'tlp' => '082345678901',
        ]);
    }
}
