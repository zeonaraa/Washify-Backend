<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Outlet;
use Faker\Factory as Faker;

class OutletSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Outlet::create([
                'nama' => $faker->company,
                'alamat' => $faker->address,
                'tlp' => $faker->phoneNumber,
            ]);
        }
    }
}
