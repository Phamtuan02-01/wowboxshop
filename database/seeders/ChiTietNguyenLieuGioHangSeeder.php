<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChiTietNguyenLieuGioHangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('chi_tiet_nguyen_lieu_gio_hang')->insert([
            // Chi tiết nguyên liệu cho Green Detox Smoothie (chi_tiet_gio_hang_id = 1)
            ['ma_chi_tiet_gio_hang' => 1, 'ma_nguyen_lieu' => 1, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_chi_tiet_gio_hang' => 1, 'ma_nguyen_lieu' => 5, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Brok-cô-li
            ['ma_chi_tiet_gio_hang' => 1, 'ma_nguyen_lieu' => 6, 'so_luong' => 200, 'created_at' => now(), 'updated_at' => now()], // Chuối
            ['ma_chi_tiet_gio_hang' => 1, 'ma_nguyen_lieu' => 10, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Bơ
            
            // Chi tiết nguyên liệu cho Caesar Salad (chi_tiet_gio_hang_id = 2)
            ['ma_chi_tiet_gio_hang' => 2, 'ma_nguyen_lieu' => 1, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_chi_tiet_gio_hang' => 2, 'ma_nguyen_lieu' => 11, 'so_luong' => 120, 'created_at' => now(), 'updated_at' => now()], // Ức gà
            ['ma_chi_tiet_gio_hang' => 2, 'ma_nguyen_lieu' => 20, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Bánh mì nguyên cám
            ['ma_chi_tiet_gio_hang' => 2, 'ma_nguyen_lieu' => 21, 'so_luong' => 10, 'created_at' => now(), 'updated_at' => now()], // Dầu olive
            
            // Chi tiết nguyên liệu cho Fresh Carrot Juice (chi_tiet_gio_hang_id = 3)
            ['ma_chi_tiet_gio_hang' => 3, 'ma_nguyen_lieu' => 4, 'so_luong' => 200, 'created_at' => now(), 'updated_at' => now()], // Cà rốt
            
            // Chi tiết nguyên liệu cho Berry Antioxidant Smoothie (chi_tiet_gio_hang_id = 4)
            ['ma_chi_tiet_gio_hang' => 4, 'ma_nguyen_lieu' => 7, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Dâu tây
            ['ma_chi_tiet_gio_hang' => 4, 'ma_nguyen_lieu' => 8, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Việt quất
            ['ma_chi_tiet_gio_hang' => 4, 'ma_nguyen_lieu' => 6, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Chuối
            ['ma_chi_tiet_gio_hang' => 4, 'ma_nguyen_lieu' => 24, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Sữa chua Hy Lạp
            
            // Chi tiết nguyên liệu cho Salmon Avocado Salad (chi_tiet_gio_hang_id = 5)
            ['ma_chi_tiet_gio_hang' => 5, 'ma_nguyen_lieu' => 1, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_chi_tiet_gio_hang' => 5, 'ma_nguyen_lieu' => 12, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Cá hồi
            ['ma_chi_tiet_gio_hang' => 5, 'ma_nguyen_lieu' => 10, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Bơ
            ['ma_chi_tiet_gio_hang' => 5, 'ma_nguyen_lieu' => 2, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Cà chua
            
            // Chi tiết nguyên liệu cho Power Protein Bowl (chi_tiet_gio_hang_id = 6)
            ['ma_chi_tiet_gio_hang' => 6, 'ma_nguyen_lieu' => 11, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Ức gà
            ['ma_chi_tiet_gio_hang' => 6, 'ma_nguyen_lieu' => 14, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Trứng
            ['ma_chi_tiet_gio_hang' => 6, 'ma_nguyen_lieu' => 18, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Yến mạch
            ['ma_chi_tiet_gio_hang' => 6, 'ma_nguyen_lieu' => 15, 'so_luong' => 20, 'created_at' => now(), 'updated_at' => now()], // Hạt chia
            
            // Chi tiết nguyên liệu cho Tropical Paradise Smoothie (chi_tiet_gio_hang_id = 7)
            ['ma_chi_tiet_gio_hang' => 7, 'ma_nguyen_lieu' => 9, 'so_luong' => 240, 'created_at' => now(), 'updated_at' => now()], // Xoài (x2 quantity)
            ['ma_chi_tiet_gio_hang' => 7, 'ma_nguyen_lieu' => 6, 'so_luong' => 160, 'created_at' => now(), 'updated_at' => now()], // Chuối (x2 quantity)
            ['ma_chi_tiet_gio_hang' => 7, 'ma_nguyen_lieu' => 26, 'so_luong' => 300, 'created_at' => now(), 'updated_at' => now()], // Sữa hạnh nhân (x2 quantity)
            
            // Chi tiết nguyên liệu cho Chicken Avocado Wrap (chi_tiet_gio_hang_id = 8)
            ['ma_chi_tiet_gio_hang' => 8, 'ma_nguyen_lieu' => 20, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Bánh mì nguyên cám
            ['ma_chi_tiet_gio_hang' => 8, 'ma_nguyen_lieu' => 11, 'so_luong' => 120, 'created_at' => now(), 'updated_at' => now()], // Ức gà
            ['ma_chi_tiet_gio_hang' => 8, 'ma_nguyen_lieu' => 10, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Bơ
            ['ma_chi_tiet_gio_hang' => 8, 'ma_nguyen_lieu' => 1, 'so_luong' => 40, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_chi_tiet_gio_hang' => 8, 'ma_nguyen_lieu' => 2, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Cà chua
        ]);
    }
}