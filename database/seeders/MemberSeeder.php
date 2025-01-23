<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Member;
use Carbon\Carbon;

class MemberSeeder extends Seeder
{
    public function run()
    {
        $months = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December'
        ];

        for ($i = 1; $i <= 80; $i++) {
            $monthIndex = ($i - 1) % 12;
            $year = 2024;
            $date = Carbon::createFromFormat('Y-m-d', "$year-" . ($monthIndex + 1) . "-01");

            Member::create([
                'nama' => "Member $i",
                'alamat' => "Jl. Contoh No. $i",
                'jenis_kelamin' => $i % 2 === 0 ? 'L' : 'P',
                'tlp' => '08' . mt_rand(1000000000, 9999999999),
                'id_outlet' => rand(1, 2),
                'created_at' => $date,
                'updated_at' => $date
            ]);
        }
    }
}
