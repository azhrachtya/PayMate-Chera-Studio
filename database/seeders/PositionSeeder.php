<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['position_name' => 'Store Manager', 'level' => 1],
            ['position_name' => 'Assistant Store Manager', 'level' => 2],
            ['position_name' => 'Senior Tea Barista', 'level' => 3],
            ['position_name' => 'Tea Barista', 'level' => 4],
            ['position_name' => 'Part Time Tea Barista', 'level' => 5],
        ];

        foreach ($positions as $p) {
            Position::updateOrCreate(['position_name' => $p['position_name']], $p);
        }
    }
}