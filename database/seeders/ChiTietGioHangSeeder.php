<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietGioHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chi_tiet_gio_hang')->insert([
            // Giỏ hàng customer01 (ma_gio_hang = 1)
            [
                'ma_gio_hang' => 1,
                'ma_san_pham' => 1, // Green Detox Smoothie
                'ma_bien_the' => 2, // Medium size
                'so_luong' => 2,
                'created_at' => now()->subDays(1),
                'updated_at' => now()
            ],
            [
                'ma_gio_hang' => 1,
                'ma_san_pham' => 6, // Caesar Salad
                'ma_bien_the' => 14, // Regular size
                'so_luong' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now()
            ],
            [
                'ma_gio_hang' => 1,
                'ma_san_pham' => 4, // Fresh Carrot Juice
                'ma_bien_the' => 10, // Small size
                'so_luong' => 1,
                'created_at' => now()->subHours(12),
                'updated_at' => now()
            ],
            
            // Giỏ hàng customer02 (ma_gio_hang = 2)
            [
                'ma_gio_hang' => 2,
                'ma_san_pham' => 2, // Berry Antioxidant Smoothie
                'ma_bien_the' => 5, // Medium size
                'so_luong' => 1,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subHours(5)
            ],
            [
                'ma_gio_hang' => 2,
                'ma_san_pham' => 7, // Salmon Avocado Salad
                'ma_bien_the' => 16, // Regular size
                'so_luong' => 1,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subHours(5)
            ],
            [
                'ma_gio_hang' => 2,
                'ma_san_pham' => 9, // Power Protein Bowl
                'ma_bien_the' => 20, // Regular size
                'so_luong' => 1,
                'created_at' => now()->subHours(8),
                'updated_at' => now()->subHours(5)
            ],
            
            // Giỏ hàng customer03 (ma_gio_hang = 3)
            [
                'ma_gio_hang' => 3,
                'ma_san_pham' => 3, // Tropical Paradise Smoothie
                'ma_bien_the' => 8, // Medium size
                'so_luong' => 2,
                'created_at' => now()->subHours(3),
                'updated_at' => now()->subHours(1)
            ],
            [
                'ma_gio_hang' => 3,
                'ma_san_pham' => 11, // Chicken Avocado Wrap
                'ma_bien_the' => 24, // Regular size
                'so_luong' => 1,
                'created_at' => now()->subHours(2),
                'updated_at' => now()->subHours(1)
            ]
        ]);
    }
}