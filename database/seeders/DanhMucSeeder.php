<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhMucSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('danh_muc')->insert([
            // Danh mục cha
            ['ma_danh_muc' => 1, 'ten_danh_muc' => 'Đồ uống', 'ma_danh_muc_cha' => null, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 2, 'ten_danh_muc' => 'Món chính', 'ma_danh_muc_cha' => null, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 3, 'ten_danh_muc' => 'Món phụ', 'ma_danh_muc_cha' => null, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 4, 'ten_danh_muc' => 'Tráng miệng', 'ma_danh_muc_cha' => null, 'created_at' => now(), 'updated_at' => now()],
            
            // Danh mục con của Đồ uống
            ['ma_danh_muc' => 5, 'ten_danh_muc' => 'Nước ép', 'ma_danh_muc_cha' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 6, 'ten_danh_muc' => 'Smoothie', 'ma_danh_muc_cha' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 7, 'ten_danh_muc' => 'Trà sữa', 'ma_danh_muc_cha' => 1, 'created_at' => now(), 'updated_at' => now()],
            
            // Danh mục con của Món chính
            ['ma_danh_muc' => 8, 'ten_danh_muc' => 'Salad', 'ma_danh_muc_cha' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 9, 'ten_danh_muc' => 'Bowl', 'ma_danh_muc_cha' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['ma_danh_muc' => 10, 'ten_danh_muc' => 'Wrap', 'ma_danh_muc_cha' => 2, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}