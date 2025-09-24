<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NguyenLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nguyen_lieu')->insert([
            // Rau củ
            ['ten_nguyen_lieu' => 'Xà lách', 'ma_nhom_nguyen_lieu' => 1, 'hinh_anh_url' => 'images/xa-lach.jpg', 'gia' => 5000, 'khoi_luong_tinh' => 100, 'calo' => 15, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Cà chua', 'ma_nhom_nguyen_lieu' => 1, 'hinh_anh_url' => 'images/ca-chua.jpg', 'gia' => 8000, 'khoi_luong_tinh' => 100, 'calo' => 18, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Dưa chuột', 'ma_nhom_nguyen_lieu' => 1, 'hinh_anh_url' => 'images/dua-chuot.jpg', 'gia' => 6000, 'khoi_luong_tinh' => 100, 'calo' => 16, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Cà rốt', 'ma_nhom_nguyen_lieu' => 1, 'hinh_anh_url' => 'images/ca-rot.jpg', 'gia' => 7000, 'khoi_luong_tinh' => 100, 'calo' => 41, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Brok-cô-li', 'ma_nhom_nguyen_lieu' => 1, 'hinh_anh_url' => 'images/broccoli.jpg', 'gia' => 12000, 'khoi_luong_tinh' => 100, 'calo' => 34, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            
            // Trái cây
            ['ten_nguyen_lieu' => 'Chuối', 'ma_nhom_nguyen_lieu' => 2, 'hinh_anh_url' => 'images/chuoi.jpg', 'gia' => 15000, 'khoi_luong_tinh' => 100, 'calo' => 89, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Dâu tây', 'ma_nhom_nguyen_lieu' => 2, 'hinh_anh_url' => 'images/dau-tay.jpg', 'gia' => 25000, 'khoi_luong_tinh' => 100, 'calo' => 32, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Việt quất', 'ma_nhom_nguyen_lieu' => 2, 'hinh_anh_url' => 'images/viet-quat.jpg', 'gia' => 35000, 'khoi_luong_tinh' => 100, 'calo' => 57, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Xoài', 'ma_nhom_nguyen_lieu' => 2, 'hinh_anh_url' => 'images/xoai.jpg', 'gia' => 20000, 'khoi_luong_tinh' => 100, 'calo' => 60, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Bơ', 'ma_nhom_nguyen_lieu' => 2, 'hinh_anh_url' => 'images/bo.jpg', 'gia' => 40000, 'khoi_luong_tinh' => 100, 'calo' => 160, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            
            // Protein
            ['ten_nguyen_lieu' => 'Ức gà', 'ma_nhom_nguyen_lieu' => 3, 'hinh_anh_url' => 'images/uc-ga.jpg', 'gia' => 45000, 'khoi_luong_tinh' => 100, 'calo' => 165, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Cá hồi', 'ma_nhom_nguyen_lieu' => 3, 'hinh_anh_url' => 'images/ca-hoi.jpg', 'gia' => 80000, 'khoi_luong_tinh' => 100, 'calo' => 208, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Tôm', 'ma_nhom_nguyen_lieu' => 3, 'hinh_anh_url' => 'images/tom.jpg', 'gia' => 120000, 'khoi_luong_tinh' => 100, 'calo' => 85, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Trứng', 'ma_nhom_nguyen_lieu' => 3, 'hinh_anh_url' => 'images/trung.jpg', 'gia' => 30000, 'khoi_luong_tinh' => 100, 'calo' => 155, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            
            // Hạt và Seeds
            ['ten_nguyen_lieu' => 'Hạt chia', 'ma_nhom_nguyen_lieu' => 4, 'hinh_anh_url' => 'images/hat-chia.jpg', 'gia' => 50000, 'khoi_luong_tinh' => 100, 'calo' => 486, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Hạt lanh', 'ma_nhom_nguyen_lieu' => 4, 'hinh_anh_url' => 'images/hat-lanh.jpg', 'gia' => 45000, 'khoi_luong_tinh' => 100, 'calo' => 534, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Hạt hạnh nhân', 'ma_nhom_nguyen_lieu' => 4, 'hinh_anh_url' => 'images/hanh-nhan.jpg', 'gia' => 120000, 'khoi_luong_tinh' => 100, 'calo' => 579, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            
            // Ngũ cốc
            ['ten_nguyen_lieu' => 'Yến mạch', 'ma_nhom_nguyen_lieu' => 5, 'hinh_anh_url' => 'images/yen-mach.jpg', 'gia' => 25000, 'khoi_luong_tinh' => 100, 'calo' => 389, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Gạo lứt', 'ma_nhom_nguyen_lieu' => 5, 'hinh_anh_url' => 'images/gao-lut.jpg', 'gia' => 18000, 'khoi_luong_tinh' => 100, 'calo' => 370, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Bánh mì nguyên cám', 'ma_nhom_nguyen_lieu' => 5, 'hinh_anh_url' => 'images/banh-mi-nguyen-cam.jpg', 'gia' => 35000, 'khoi_luong_tinh' => 100, 'calo' => 247, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            
            // Gia vị
            ['ten_nguyen_lieu' => 'Dầu olive', 'ma_nhom_nguyen_lieu' => 6, 'hinh_anh_url' => 'images/dau-olive.jpg', 'gia' => 80000, 'khoi_luong_tinh' => 100, 'calo' => 884, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Giấm táo', 'ma_nhom_nguyen_lieu' => 6, 'hinh_anh_url' => 'images/giam-tao.jpg', 'gia' => 25000, 'khoi_luong_tinh' => 100, 'calo' => 22, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Muối hồng', 'ma_nhom_nguyen_lieu' => 6, 'hinh_anh_url' => 'images/muoi-hong.jpg', 'gia' => 15000, 'khoi_luong_tinh' => 100, 'calo' => 0, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            
            // Sữa và chế phẩm
            ['ten_nguyen_lieu' => 'Sữa chua Hy Lạp', 'ma_nhom_nguyen_lieu' => 7, 'hinh_anh_url' => 'images/sua-chua-hy-lap.jpg', 'gia' => 35000, 'khoi_luong_tinh' => 100, 'calo' => 59, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Phô mai cottage', 'ma_nhom_nguyen_lieu' => 7, 'hinh_anh_url' => 'images/pho-mai-cottage.jpg', 'gia' => 45000, 'khoi_luong_tinh' => 100, 'calo' => 98, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
            ['ten_nguyen_lieu' => 'Sữa hạnh nhân', 'ma_nhom_nguyen_lieu' => 7, 'hinh_anh_url' => 'images/sua-hanh-nhan.jpg', 'gia' => 40000, 'khoi_luong_tinh' => 100, 'calo' => 17, 'ngay_tao' => now(), 'ngay_cap_nhat' => now()],
        ]);
    }
}