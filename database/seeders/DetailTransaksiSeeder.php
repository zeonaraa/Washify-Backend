<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailTransaksi;

class DetailTransaksiSeeder extends Seeder
{
    public function run()
    {
        DetailTransaksi::create([
            'id_transaksi' => 1,
            'id_paket' => 1,
            'qty' => 2,
            'keterangan' => 'Pakaian biasa',
        ]);

        DetailTransaksi::create([
            'id_transaksi' => 1,
            'id_paket' => 2,
            'qty' => 1,
            'keterangan' => 'Selimut tebal',
        ]);
    }
}
