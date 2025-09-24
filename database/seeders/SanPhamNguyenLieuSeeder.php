<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanPhamNguyenLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('san_pham_nguyen_lieu')->insert([
            // Green Detox Smoothie (ID: 1)
            ['ma_san_pham' => 1, 'ma_nguyen_lieu' => 1, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 1, 'ma_nguyen_lieu' => 5, 'so_luong' => 30, 'created_at' => now(), 'updated_at' => now()], // Brok-cô-li
            ['ma_san_pham' => 1, 'ma_nguyen_lieu' => 6, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Chuối
            ['ma_san_pham' => 1, 'ma_nguyen_lieu' => 10, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Bơ
            
            // Berry Antioxidant Smoothie (ID: 2)
            ['ma_san_pham' => 2, 'ma_nguyen_lieu' => 7, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Dâu tây
            ['ma_san_pham' => 2, 'ma_nguyen_lieu' => 8, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Việt quất
            ['ma_san_pham' => 2, 'ma_nguyen_lieu' => 6, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Chuối
            ['ma_san_pham' => 2, 'ma_nguyen_lieu' => 24, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Sữa chua Hy Lạp
            
            // Tropical Paradise Smoothie (ID: 3)
            ['ma_san_pham' => 3, 'ma_nguyen_lieu' => 9, 'so_luong' => 120, 'created_at' => now(), 'updated_at' => now()], // Xoài
            ['ma_san_pham' => 3, 'ma_nguyen_lieu' => 6, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Chuối
            ['ma_san_pham' => 3, 'ma_nguyen_lieu' => 26, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Sữa hạnh nhân
            
            // Fresh Carrot Juice (ID: 4)
            ['ma_san_pham' => 4, 'ma_nguyen_lieu' => 4, 'so_luong' => 200, 'created_at' => now(), 'updated_at' => now()], // Cà rốt
            
            // Green Vegetable Juice (ID: 5)
            ['ma_san_pham' => 5, 'ma_nguyen_lieu' => 1, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 5, 'ma_nguyen_lieu' => 3, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Dưa chuột
            ['ma_san_pham' => 5, 'ma_nguyen_lieu' => 4, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Cà rốt
            ['ma_san_pham' => 5, 'ma_nguyen_lieu' => 5, 'so_luong' => 70, 'created_at' => now(), 'updated_at' => now()], // Brok-cô-li
            
            // Caesar Salad (ID: 6)
            ['ma_san_pham' => 6, 'ma_nguyen_lieu' => 1, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 6, 'ma_nguyen_lieu' => 11, 'so_luong' => 120, 'created_at' => now(), 'updated_at' => now()], // Ức gà
            ['ma_san_pham' => 6, 'ma_nguyen_lieu' => 20, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Bánh mì nguyên cám
            ['ma_san_pham' => 6, 'ma_nguyen_lieu' => 21, 'so_luong' => 10, 'created_at' => now(), 'updated_at' => now()], // Dầu olive
            
            // Salmon Avocado Salad (ID: 7)
            ['ma_san_pham' => 7, 'ma_nguyen_lieu' => 1, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 7, 'ma_nguyen_lieu' => 12, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Cá hồi
            ['ma_san_pham' => 7, 'ma_nguyen_lieu' => 10, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Bơ
            ['ma_san_pham' => 7, 'ma_nguyen_lieu' => 2, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Cà chua
            ['ma_san_pham' => 7, 'ma_nguyen_lieu' => 21, 'so_luong' => 15, 'created_at' => now(), 'updated_at' => now()], // Dầu olive
            
            // Mediterranean Salad (ID: 8)
            ['ma_san_pham' => 8, 'ma_nguyen_lieu' => 1, 'so_luong' => 120, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 8, 'ma_nguyen_lieu' => 2, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Cà chua
            ['ma_san_pham' => 8, 'ma_nguyen_lieu' => 3, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Dưa chuột
            ['ma_san_pham' => 8, 'ma_nguyen_lieu' => 21, 'so_luong' => 20, 'created_at' => now(), 'updated_at' => now()], // Dầu olive
            ['ma_san_pham' => 8, 'ma_nguyen_lieu' => 22, 'so_luong' => 10, 'created_at' => now(), 'updated_at' => now()], // Giấm táo
            
            // Power Protein Bowl (ID: 9)
            ['ma_san_pham' => 9, 'ma_nguyen_lieu' => 11, 'so_luong' => 150, 'created_at' => now(), 'updated_at' => now()], // Ức gà
            ['ma_san_pham' => 9, 'ma_nguyen_lieu' => 14, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Trứng
            ['ma_san_pham' => 9, 'ma_nguyen_lieu' => 18, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Yến mạch
            ['ma_san_pham' => 9, 'ma_nguyen_lieu' => 15, 'so_luong' => 20, 'created_at' => now(), 'updated_at' => now()], // Hạt chia
            ['ma_san_pham' => 9, 'ma_nguyen_lieu' => 17, 'so_luong' => 30, 'created_at' => now(), 'updated_at' => now()], // Hạt hạnh nhân
            
            // Quinoa Buddha Bowl (ID: 10)
            ['ma_san_pham' => 10, 'ma_nguyen_lieu' => 19, 'so_luong' => 100, 'created_at' => now(), 'updated_at' => now()], // Gạo lứt
            ['ma_san_pham' => 10, 'ma_nguyen_lieu' => 1, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 10, 'ma_nguyen_lieu' => 4, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Cà rốt
            ['ma_san_pham' => 10, 'ma_nguyen_lieu' => 5, 'so_luong' => 70, 'created_at' => now(), 'updated_at' => now()], // Brok-cô-li
            ['ma_san_pham' => 10, 'ma_nguyen_lieu' => 10, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Bơ
            
            // Chicken Avocado Wrap (ID: 11)
            ['ma_san_pham' => 11, 'ma_nguyen_lieu' => 20, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Bánh mì nguyên cám
            ['ma_san_pham' => 11, 'ma_nguyen_lieu' => 11, 'so_luong' => 120, 'created_at' => now(), 'updated_at' => now()], // Ức gà
            ['ma_san_pham' => 11, 'ma_nguyen_lieu' => 10, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Bơ
            ['ma_san_pham' => 11, 'ma_nguyen_lieu' => 1, 'so_luong' => 40, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 11, 'ma_nguyen_lieu' => 2, 'so_luong' => 50, 'created_at' => now(), 'updated_at' => now()], // Cà chua
            
            // Veggie Hummus Wrap (ID: 12)
            ['ma_san_pham' => 12, 'ma_nguyen_lieu' => 20, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Bánh mì nguyên cám
            ['ma_san_pham' => 12, 'ma_nguyen_lieu' => 1, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Xà lách
            ['ma_san_pham' => 12, 'ma_nguyen_lieu' => 2, 'so_luong' => 80, 'created_at' => now(), 'updated_at' => now()], // Cà chua
            ['ma_san_pham' => 12, 'ma_nguyen_lieu' => 3, 'so_luong' => 60, 'created_at' => now(), 'updated_at' => now()], // Dưa chuột
            ['ma_san_pham' => 12, 'ma_nguyen_lieu' => 4, 'so_luong' => 40, 'created_at' => now(), 'updated_at' => now()], // Cà rốt
        ]);
    }
}