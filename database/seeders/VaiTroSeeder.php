<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaiTroSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vai_tro')->insert([
            [
                'ma_vai_tro' => 1,
                'ten_vai_tro' => 'Admin',
                'created_at' => '2025-10-09 00:00:00',
                'updated_at' => '2025-10-09 00:00:00',
            ],
            [
                'ma_vai_tro' => 2,
                'ten_vai_tro' => 'Khach hang',
                'created_at' => '2025-10-09 00:00:00',
                'updated_at' => '2025-10-09 00:00:00',
            ],
            [
                'ma_vai_tro' => 3,
                'ten_vai_tro' => 'Quan ly',
                'created_at' => '2025-10-09 00:00:00',
                'updated_at' => '2025-10-09 00:00:00',
            ],
        ]);
    }
}
