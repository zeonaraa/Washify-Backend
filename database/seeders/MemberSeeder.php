<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;

class MemberSeeder extends Seeder
{
    public function run()
    {
        Member::create([
            'nama' => 'John Doe',
            'alamat' => 'Jl. Sembarang No. 1',
            'jenis_kelamin' => 'L',
            'tlp' => '089876543210',
        ]);

        Member::create([
            'nama' => 'Jane Doe',
            'alamat' => 'Jl. Sembarang No. 2',
            'jenis_kelamin' => 'P',
            'tlp' => '081234567891',
        ]);
    }
}
