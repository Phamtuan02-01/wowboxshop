<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GioHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('gio_hang')->insert([
            // Giỏ hàng cho customer01
            [
                'ma_tai_khoan' => 3,
                'ngay_tao' => now()->subDays(1),
                'ngay_cap_nhat' => now()
            ],
            
            // Giỏ hàng cho customer02
            [
                'ma_tai_khoan' => 4,
                'ngay_tao' => now()->subDays(2),
                'ngay_cap_nhat' => now()->subHours(5)
            ],
            
            // Giỏ hàng cho customer03
            [
                'ma_tai_khoan' => 5,
                'ngay_tao' => now()->subHours(3),
                'ngay_cap_nhat' => now()->subHours(1)
            ]
        ]);
    }
}