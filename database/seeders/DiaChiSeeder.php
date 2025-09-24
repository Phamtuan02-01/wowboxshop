<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DiaChiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dia_chi')->insert([
            // Địa chỉ cho admin
            [
                'ma_tai_khoan' => 1,
                'ho_ten' => 'Admin WowBox',
                'thanh_pho' => 'TP. Hồ Chí Minh',
                'quan_huyen' => 'Quận 1',
                'dia_chi_chi_tiet' => '123 Nguyễn Huệ, Phường Bến Nghé',
                'so_dien_thoai' => '0123456789',
                'la_mac_dinh' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Địa chỉ cho manager
            [
                'ma_tai_khoan' => 2,
                'ho_ten' => 'Nguyễn Văn Manager',
                'thanh_pho' => 'TP. Hồ Chí Minh',
                'quan_huyen' => 'Quận 3',
                'dia_chi_chi_tiet' => '456 Võ Văn Tần, Phường Võ Thị Sáu',
                'so_dien_thoai' => '0987654321',
                'la_mac_dinh' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Địa chỉ cho customer01
            [
                'ma_tai_khoan' => 3,
                'ho_ten' => 'Nguyễn Văn A',
                'thanh_pho' => 'TP. Hồ Chí Minh',
                'quan_huyen' => 'Quận 7',
                'dia_chi_chi_tiet' => '789 Nguyễn Thị Thập, Phường Tân Phú',
                'so_dien_thoai' => '0901234567',
                'la_mac_dinh' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_tai_khoan' => 3,
                'ho_ten' => 'Nguyễn Văn A',
                'thanh_pho' => 'TP. Hồ Chí Minh',
                'quan_huyen' => 'Quận 1',
                'dia_chi_chi_tiet' => '321 Lê Lợi, Phường Bến Thành',
                'so_dien_thoai' => '0901234567',
                'la_mac_dinh' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Địa chỉ cho customer02
            [
                'ma_tai_khoan' => 4,
                'ho_ten' => 'Trần Thị B',
                'thanh_pho' => 'Hà Nội',
                'quan_huyen' => 'Quận Hai Bà Trưng',
                'dia_chi_chi_tiet' => '147 Bà Triệu, Phường Lê Đại Hành',
                'so_dien_thoai' => '0912345678',
                'la_mac_dinh' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Địa chỉ cho customer03
            [
                'ma_tai_khoan' => 5,
                'ho_ten' => 'Lê Thị C',
                'thanh_pho' => 'Đà Nẵng',
                'quan_huyen' => 'Quận Hải Châu',
                'dia_chi_chi_tiet' => '258 Trần Phú, Phường Thạch Thang',
                'so_dien_thoai' => '0923456789',
                'la_mac_dinh' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ]
        ]);
    }
}