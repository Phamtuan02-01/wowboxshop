<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DanhGiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('danh_gia')->insert([
            // Đánh giá cho Green Detox Smoothie
            [
                'ma_san_pham' => 1,
                'ma_tai_khoan' => 3,
                'sao' => 5,
                'binh_luan' => 'Smoothie rất ngon và tươi mát, cảm thấy cơ thể nhẹ nhàng hơn sau khi uống!',
                'ngay_tao' => now()->subDays(2),
                'ngay_cap_nhat' => now()->subDays(2)
            ],
            [
                'ma_san_pham' => 1,
                'ma_tai_khoan' => 4,
                'sao' => 4,
                'binh_luan' => 'Vị hơi đậm rau xanh nhưng rất healthy, sẽ order lại',
                'ngay_tao' => now()->subDays(1),
                'ngay_cap_nhat' => now()->subDays(1)
            ],
            
            // Đánh giá cho Berry Antioxidant Smoothie
            [
                'ma_san_pham' => 2,
                'ma_tai_khoan' => 3,
                'sao' => 5,
                'binh_luan' => 'Smoothie berry ngon tuyệt! Vị ngọt tự nhiên từ trái cây, không cần thêm đường',
                'ngay_tao' => now()->subDays(3),
                'ngay_cap_nhat' => now()->subDays(3)
            ],
            [
                'ma_san_pham' => 2,
                'ma_tai_khoan' => 5,
                'sao' => 5,
                'binh_luan' => 'Màu sắc đẹp mắt, vị ngon và giàu dinh dưỡng. Highly recommended!',
                'ngay_tao' => now()->subDays(1),
                'ngay_cap_nhat' => now()->subDays(1)
            ],
            
            // Đánh giá cho Caesar Salad
            [
                'ma_san_pham' => 6,
                'ma_tai_khoan' => 4,
                'sao' => 4,
                'binh_luan' => 'Salad Caesar rất tuyệt, gà nướng thơm ngon, rau tươi giòn',
                'ngay_tao' => now()->subDays(4),
                'ngay_cap_nhat' => now()->subDays(4)
            ],
            [
                'ma_san_pham' => 6,
                'ma_tai_khoan' => 5,
                'sao' => 5,
                'binh_luan' => 'Món salad yêu thích của tôi! Vừa ngon vừa no, phù hợp cho bữa trưa',
                'ngay_tao' => now()->subDays(2),
                'ngay_cap_nhat' => now()->subDays(2)
            ],
            
            // Đánh giá cho Salmon Avocado Salad
            [
                'ma_san_pham' => 7,
                'ma_tai_khoan' => 3,
                'sao' => 5,
                'binh_luan' => 'Cá hồi tươi ngon, bơ chín vừa tầm. Món ăn healthy và ngon miệng!',
                'ngay_tao' => now()->subDays(1),
                'ngay_cap_nhat' => now()->subDays(1)
            ],
            
            // Đánh giá cho Power Protein Bowl
            [
                'ma_san_pham' => 9,
                'ma_tai_khoan' => 4,
                'sao' => 4,
                'binh_luan' => 'Bowl protein rất no và bổ dưỡng, phù hợp cho người tập gym như tôi',
                'ngay_tao' => now()->subDays(3),
                'ngay_cap_nhat' => now()->subDays(3)
            ],
            [
                'ma_san_pham' => 9,
                'ma_tai_khoan' => 5,
                'sao' => 5,
                'binh_luan' => 'Đầy đủ dinh dưỡng, ngon miệng và trình bày đẹp mắt. Perfect!',
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Đánh giá cho Fresh Carrot Juice
            [
                'ma_san_pham' => 4,
                'ma_tai_khoan' => 3,
                'sao' => 4,
                'binh_luan' => 'Nước ép cà rốt tươi ngon, ngọt tự nhiên và rất tốt cho mắt',
                'ngay_tao' => now()->subDays(2),
                'ngay_cap_nhat' => now()->subDays(2)
            ],
            
            // Đánh giá cho Mediterranean Salad
            [
                'ma_san_pham' => 8,
                'ma_tai_khoan' => 4,
                'sao' => 4,
                'binh_luan' => 'Salad Địa Trung Hải tươi mát, dressing vừa vặn không quá chua',
                'ngay_tao' => now()->subDays(1),
                'ngay_cap_nhat' => now()->subDays(1)
            ]
        ]);
    }
}