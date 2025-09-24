<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BienTheSanPhamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bien_the_san_pham')->insert([
            // Green Detox Smoothie - kích thước khác nhau
            ['ma_san_pham' => 1, 'kich_thuoc' => 'Small (300ml)', 'gia' => 45000, 'calo' => 180, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 1, 'kich_thuoc' => 'Medium (500ml)', 'gia' => 65000, 'calo' => 300, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 1, 'kich_thuoc' => 'Large (700ml)', 'gia' => 85000, 'calo' => 420, 'created_at' => now(), 'updated_at' => now()],
            
            // Berry Antioxidant Smoothie
            ['ma_san_pham' => 2, 'kich_thuoc' => 'Small (300ml)', 'gia' => 55000, 'calo' => 220, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 2, 'kich_thuoc' => 'Medium (500ml)', 'gia' => 75000, 'calo' => 360, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 2, 'kich_thuoc' => 'Large (700ml)', 'gia' => 95000, 'calo' => 500, 'created_at' => now(), 'updated_at' => now()],
            
            // Tropical Paradise Smoothie
            ['ma_san_pham' => 3, 'kich_thuoc' => 'Small (300ml)', 'gia' => 50000, 'calo' => 200, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 3, 'kich_thuoc' => 'Medium (500ml)', 'gia' => 70000, 'calo' => 340, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 3, 'kich_thuoc' => 'Large (700ml)', 'gia' => 90000, 'calo' => 480, 'created_at' => now(), 'updated_at' => now()],
            
            // Fresh Carrot Juice
            ['ma_san_pham' => 4, 'kich_thuoc' => 'Small (250ml)', 'gia' => 30000, 'calo' => 100, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 4, 'kich_thuoc' => 'Medium (400ml)', 'gia' => 45000, 'calo' => 160, 'created_at' => now(), 'updated_at' => now()],
            
            // Green Vegetable Juice
            ['ma_san_pham' => 5, 'kich_thuoc' => 'Small (250ml)', 'gia' => 35000, 'calo' => 80, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 5, 'kich_thuoc' => 'Medium (400ml)', 'gia' => 50000, 'calo' => 120, 'created_at' => now(), 'updated_at' => now()],
            
            // Caesar Salad
            ['ma_san_pham' => 6, 'kich_thuoc' => 'Regular', 'gia' => 85000, 'calo' => 450, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 6, 'kich_thuoc' => 'Large', 'gia' => 120000, 'calo' => 650, 'created_at' => now(), 'updated_at' => now()],
            
            // Salmon Avocado Salad
            ['ma_san_pham' => 7, 'kich_thuoc' => 'Regular', 'gia' => 125000, 'calo' => 520, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 7, 'kich_thuoc' => 'Large', 'gia' => 165000, 'calo' => 720, 'created_at' => now(), 'updated_at' => now()],
            
            // Mediterranean Salad
            ['ma_san_pham' => 8, 'kich_thuoc' => 'Regular', 'gia' => 75000, 'calo' => 350, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 8, 'kich_thuoc' => 'Large', 'gia' => 105000, 'calo' => 500, 'created_at' => now(), 'updated_at' => now()],
            
            // Power Protein Bowl
            ['ma_san_pham' => 9, 'kich_thuoc' => 'Regular', 'gia' => 95000, 'calo' => 580, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 9, 'kich_thuoc' => 'Large', 'gia' => 130000, 'calo' => 800, 'created_at' => now(), 'updated_at' => now()],
            
            // Quinoa Buddha Bowl
            ['ma_san_pham' => 10, 'kich_thuoc' => 'Regular', 'gia' => 80000, 'calo' => 480, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 10, 'kich_thuoc' => 'Large', 'gia' => 110000, 'calo' => 680, 'created_at' => now(), 'updated_at' => now()],
            
            // Chicken Avocado Wrap
            ['ma_san_pham' => 11, 'kich_thuoc' => 'Regular', 'gia' => 70000, 'calo' => 420, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 11, 'kich_thuoc' => 'Large', 'gia' => 95000, 'calo' => 580, 'created_at' => now(), 'updated_at' => now()],
            
            // Veggie Hummus Wrap
            ['ma_san_pham' => 12, 'kich_thuoc' => 'Regular', 'gia' => 55000, 'calo' => 320, 'created_at' => now(), 'updated_at' => now()],
            ['ma_san_pham' => 12, 'kich_thuoc' => 'Large', 'gia' => 75000, 'calo' => 450, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}