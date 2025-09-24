<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaiVietBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bai_viet_blog')->insert([
            [
                'tieu_de' => '10 Lợi Ích Của Việc Uống Smoothie Xanh Hàng Ngày',
                'noi_dung' => 'Smoothie xanh không chỉ ngon mà còn mang lại rất nhiều lợi ích cho sức khỏe. Trong bài viết này, chúng ta sẽ khám phá 10 lợi ích tuyệt vời khi bạn uống smoothie xanh hàng ngày...',
                'hinh_anh_url' => 'images/blog/green-smoothie-benefits.jpg',
                'ngay_dang' => now()->subDays(7),
                'ngay_tao' => now()->subDays(7),
                'ngay_cap_nhat' => now()->subDays(7)
            ],
            [
                'tieu_de' => 'Cách Chọn Nguyên Liệu Tươi Ngon Cho Salad Của Bạn',
                'noi_dung' => 'Một món salad ngon bắt đầu từ việc chọn lựa nguyên liệu tươi ngon. Hãy cùng tìm hiểu những bí quyết để chọn được những nguyên liệu chất lượng nhất...',
                'hinh_anh_url' => 'images/blog/fresh-ingredients.jpg',
                'ngay_dang' => now()->subDays(5),
                'ngay_tao' => now()->subDays(5),
                'ngay_cap_nhat' => now()->subDays(5)
            ],
            [
                'tieu_de' => 'Protein Bowl - Xu Hướng Ăn Uống Healthy Đang Hot',
                'noi_dung' => 'Protein bowl đang trở thành xu hướng ăn uống được yêu thích bởi tính tiện lợi và giá trị dinh dưỡng cao. Cùng khám phá cách tạo ra những protein bowl hoàn hảo...',
                'hinh_anh_url' => 'images/blog/protein-bowl-trend.jpg',
                'ngay_dang' => now()->subDays(3),
                'ngay_tao' => now()->subDays(3),
                'ngay_cap_nhat' => now()->subDays(3)
            ],
            [
                'tieu_de' => 'Detox Cơ Thể Với Nước Ép Rau Củ Tự Nhiên',
                'noi_dung' => 'Nước ép rau củ là cách tuyệt vời để detox cơ thể và bổ sung vitamin, khoáng chất. Hãy tìm hiểu về các loại nước ép rau củ tốt nhất cho sức khỏe...',
                'hinh_anh_url' => 'images/blog/vegetable-juice-detox.jpg',
                'ngay_dang' => now()->subDays(1),
                'ngay_tao' => now()->subDays(1),
                'ngay_cap_nhat' => now()->subDays(1)
            ],
            [
                'tieu_de' => 'Lối Sống Healthy: Bắt Đầu Từ Những Thay Đổi Nhỏ',
                'noi_dung' => 'Xây dựng lối sống healthy không cần phải thay đổi drastically. Hãy bắt đầu từ những thay đổi nhỏ trong chế độ ăn uống và hoạt động hàng ngày...',
                'hinh_anh_url' => 'images/blog/healthy-lifestyle.jpg',
                'ngay_dang' => now(),
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ]
        ]);
    }
}