<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TransaksiSeeder extends Seeder
{
    public function run()
    {
        // Inisialisasi Faker untuk data acak
        $faker = Faker::create();

        // Menghasilkan data transaksi untuk Januari hingga Desember 2024
        for ($i = 0; $i < 80; $i++) {
            // Tentukan tanggal acak antara Januari 2024 dan Desember 2024
            $tanggal = Carbon::create(2024, rand(1, 12), rand(1, 28));

            // Untuk memastikan lebih banyak data pada 26 Desember 2024
            if ($i < 20) { // 20 transaksi pada 26 Desember 2024
                $tanggal = Carbon::create(2024, 12, 26);
            }

            Transaksi::create([
                'id_outlet' => rand(1, 2),
                'kode_invoice' => 'INV' . str_pad(rand(1, 100), 3, '0', STR_PAD_LEFT),
                'id_member' => rand(1, 5),
                'tgl' => $tanggal,
                'batas_waktu' => $tanggal->addDays(3),
                'tgl_bayar' => $tanggal,
                'biaya_tambahan' => rand(1000, 5000),
                'diskon' => rand(0, 20),
                'pajak' => rand(500, 2000),
                'status' => ['baru', 'proses', 'selesai', 'diambil'][rand(0, 3)],
                'dibayar' => ['dibayar', 'belum_dibayar'][(1)],
                'id_user' => rand(1, 3),
            ]);
        }
    }
}
