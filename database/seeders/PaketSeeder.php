<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paket;

class PaketSeeder extends Seeder
{
    public function run()
    {
        Paket::create([
            'id_outlet' => 1,
            'jenis' => 'kiloan',
            'nama_paket' => 'Cuci Kiloan',
            'harga' => 10000,
        ]);

        Paket::create([
            'id_outlet' => 2,
            'jenis' => 'selimut',
            'nama_paket' => 'Cuci Selimut',
            'harga' => 20000,
        ]);
    }
}
