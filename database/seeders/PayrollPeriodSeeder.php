<?php

namespace Database\Seeders;

use App\Models\PayrollPeriod;
use Illuminate\Database\Seeder;

class PayrollPeriodSeeder extends Seeder
{
    
    public function run(): void
    {
        $periods = [
            ['nama_periode' => 'April 2026', 'tanggal_mulai' => '2026-03-26', 'tanggal_selesai' => '2026-04-25'],
            ['nama_periode' => 'Mei 2026', 'tanggal_mulai' => '2026-04-26', 'tanggal_selesai' => '2026-05-25'],
            ['nama_periode' => 'Juni 2026', 'tanggal_mulai' => '2026-05-26', 'tanggal_selesai' => '2026-06-25'],
        ];

        foreach ($periods as $p) {
            PayrollPeriod::updateOrCreate(
                ['nama_periode' => $p['nama_periode']],
                $p
            );
        }
    }
}