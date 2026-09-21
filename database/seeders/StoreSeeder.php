<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            ['store_id' => 'DPK01', 'store_name' => 'Chera Studio – Trans Studio Mall Cibubur', 'address' => 'Jl. Alternatif Cibubur, Depok', 'umr' => 5522662],
            ['store_id' => 'JKT01', 'store_name' => 'Chera Studio – Mall Grand Indonesia', 'address' => 'Jl. MH Thamrin No.1, Jakarta Pusat', 'umr' => 5729876],
            ['store_id' => 'BKS01', 'store_name' => 'Chera Studio – Summarecon Mall Bekasi', 'address' => 'Jl. Boulevard Ahmad Yani, Bekasi', 'umr' => 5999422],
        ];

        foreach ($stores as $s) {
            Store::updateOrCreate(['store_id' => $s['store_id']], $s);
        }
    }
}