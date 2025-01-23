<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DetailTransaksi;
use App\Models\Transaksi;
use App\Models\Paket;
use Faker\Factory as Faker;

class DetailTransaksiSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $pakets = Paket::all();
        $transaksis = Transaksi::all();

        for ($i = 0; $i < 80; $i++) {
            DetailTransaksi::create([
                'id_transaksi' => $transaksis->random()->id,
                'id_paket' => $pakets->random()->id,
                'qty' => rand(1, 5),
                'keterangan' => $faker->sentence(3),
            ]);
        }
    }
}
