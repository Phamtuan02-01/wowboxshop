<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SanPhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('san_pham')->insert([
            // Smoothie
            [
                'ten_san_pham' => 'Green Detox Smoothie',
                'ma_danh_muc' => 6, // Smoothie
                'mo_ta' => 'Smoothie xanh giàu vitamin và chất xơ, giúp detox cơ thể hiệu quả',
                'hinh_anh_url' => 'images/green-detox-smoothie.jpg',
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Berry Antioxidant Smoothie',
                'ma_danh_muc' => 6, // Smoothie
                'mo_ta' => 'Smoothie từ các loại berry tươi, giàu chất chống oxy hóa',
                'hinh_anh_url' => 'images/berry-smoothie.jpg',
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Tropical Paradise Smoothie',
                'ma_danh_muc' => 6, // Smoothie
                'mo_ta' => 'Hương vị nhiệt đới từ xoài và dứa tươi mát',
                'hinh_anh_url' => 'images/tropical-smoothie.jpg',
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Nước ép
            [
                'ten_san_pham' => 'Fresh Carrot Juice',
                'ma_danh_muc' => 5, // Nước ép
                'mo_ta' => 'Nước ép cà rốt tươi nguyên chất, giàu vitamin A',
                'hinh_anh_url' => 'images/carrot-juice.jpg',
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Green Vegetable Juice',
                'ma_danh_muc' => 5, // Nước ép
                'mo_ta' => 'Nước ép rau xanh tổng hợp, bổ sung chất xơ và vitamin',
                'hinh_anh_url' => 'images/green-juice.jpg',
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Salad
            [
                'ten_san_pham' => 'Caesar Salad',
                'ma_danh_muc' => 8, // Salad
                'mo_ta' => 'Salad Caesar truyền thống với ức gà nướng và phô mai Parmesan',
                'hinh_anh_url' => 'images/caesar-salad.jpg',
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Salmon Avocado Salad',
                'ma_danh_muc' => 8, // Salad
                'mo_ta' => 'Salad cá hồi và bơ, giàu omega-3 và chất béo tốt',
                'hinh_anh_url' => 'images/salmon-avocado-salad.jpg',
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Mediterranean Salad',
                'ma_danh_muc' => 8, // Salad
                'mo_ta' => 'Salad Địa Trung Hải với cà chua, dưa chuột và oliu',
                'hinh_anh_url' => 'images/mediterranean-salad.jpg',
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Bowl
            [
                'ten_san_pham' => 'Power Protein Bowl',
                'ma_danh_muc' => 9, // Bowl
                'mo_ta' => 'Bowl protein với ức gà, trứng và các loại hạt dinh dưỡng',
                'hinh_anh_url' => 'images/protein-bowl.jpg',
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Quinoa Buddha Bowl',
                'ma_danh_muc' => 9, // Bowl
                'mo_ta' => 'Buddha bowl với quinoa và rau củ tươi ngon',
                'hinh_anh_url' => 'images/buddha-bowl.jpg',
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Wrap
            [
                'ten_san_pham' => 'Chicken Avocado Wrap',
                'ma_danh_muc' => 10, // Wrap
                'mo_ta' => 'Wrap gà và bơ với bánh mì nguyên cám',
                'hinh_anh_url' => 'images/chicken-wrap.jpg',
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_san_pham' => 'Veggie Hummus Wrap',
                'ma_danh_muc' => 10, // Wrap
                'mo_ta' => 'Wrap chay với hummus và rau củ tươi',
                'hinh_anh_url' => 'images/veggie-wrap.jpg',
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ]
        ]);
    }
}