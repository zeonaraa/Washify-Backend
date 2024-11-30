<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        Transaksi::create([
            'id_outlet' => 1,
            'kode_invoice' => 'INV001',
            'id_member' => 1,
            'tgl' => now(),
            'batas_waktu' => now()->addDays(3),
            'tgl_bayar' => now(),
            'biaya_tambahan' => 5000,
            'diskon' => 10.0,
            'pajak' => 2000,
            'status' => 'baru',
            'dibayar' => 'dibayar',
            'id_user' => 1,
        ]);
    }
}
