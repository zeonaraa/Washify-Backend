<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            OutletSeeder::class,
            PaketSeeder::class,
            MemberSeeder::class,
            UserSeeder::class,
            TransaksiSeeder::class,
            DetailTransaksiSeeder::class,
        ]);
    }
}
