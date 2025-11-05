<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Insert sample product variants
        DB::table('bien_the_san_pham')->insert([
            // Phở Bò Tái - ma_san_pham = 1
            [
                'ma_bien_the' => 1,
                'ma_san_pham' => 1,
                'kich_thuoc' => 'Nhỏ',
                'gia' => 45000,
                'calo' => 300,
                'so_luong_ton' => 20,
                'trang_thai' => true,
                'mo_ta' => 'Phở bò tái size nhỏ',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 2,
                'ma_san_pham' => 1,
                'kich_thuoc' => 'Vừa',
                'gia' => 55000,
                'calo' => 350,
                'so_luong_ton' => 30,
                'trang_thai' => true,
                'mo_ta' => 'Phở bò tái size vừa',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 3,
                'ma_san_pham' => 1,
                'kich_thuoc' => 'Lớn',
                'gia' => 65000,
                'calo' => 400,
                'so_luong_ton' => 15,
                'trang_thai' => true,
                'mo_ta' => 'Phở bò tái size lớn',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Bánh Mì Thịt Nướng - ma_san_pham = 2
            [
                'ma_bien_the' => 4,
                'ma_san_pham' => 2,
                'kich_thuoc' => 'Thường',
                'gia' => 25000,
                'calo' => 280,
                'so_luong_ton' => 25,
                'trang_thai' => true,
                'mo_ta' => 'Bánh mì thịt nướng thường',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 5,
                'ma_san_pham' => 2,
                'kich_thuoc' => 'Đặc biệt',
                'gia' => 35000,
                'calo' => 350,
                'so_luong_ton' => 20,
                'trang_thai' => true,
                'mo_ta' => 'Bánh mì thịt nướng đặc biệt',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Cơm Tấm Sườn Nướng - ma_san_pham = 3
            [
                'ma_bien_the' => 6,
                'ma_san_pham' => 3,
                'kich_thuoc' => 'Thường',
                'gia' => 40000,
                'calo' => 400,
                'so_luong_ton' => 18,
                'trang_thai' => true,
                'mo_ta' => 'Cơm tấm sườn nướng thường',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 7,
                'ma_san_pham' => 3,
                'kich_thuoc' => 'Đặc biệt',
                'gia' => 50000,
                'calo' => 500,
                'so_luong_ton' => 12,
                'trang_thai' => true,
                'mo_ta' => 'Cơm tấm sườn nướng đặc biệt',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Bún Bò Huế - ma_san_pham = 4
            [
                'ma_bien_the' => 8,
                'ma_san_pham' => 4,
                'kich_thuoc' => 'Thường',
                'gia' => 45000,
                'calo' => 370,
                'so_luong_ton' => 22,
                'trang_thai' => true,
                'mo_ta' => 'Bún bò Huế thường',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 9,
                'ma_san_pham' => 4,
                'kich_thuoc' => 'Đặc biệt',
                'gia' => 55000,
                'calo' => 450,
                'so_luong_ton' => 15,
                'trang_thai' => true,
                'mo_ta' => 'Bún bò Huế đặc biệt',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Trà Sữa Truyền Thống - ma_san_pham = 5
            [
                'ma_bien_the' => 10,
                'ma_san_pham' => 5,
                'kich_thuoc' => 'M',
                'gia' => 30000,
                'calo' => 220,
                'so_luong_ton' => 35,
                'trang_thai' => true,
                'mo_ta' => 'Trà sữa size M',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 11,
                'ma_san_pham' => 5,
                'kich_thuoc' => 'L',
                'gia' => 35000,
                'calo' => 280,
                'so_luong_ton' => 30,
                'trang_thai' => true,
                'mo_ta' => 'Trà sữa size L',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Chè Ba Màu - ma_san_pham = 6
            [
                'ma_bien_the' => 12,
                'ma_san_pham' => 6,
                'kich_thuoc' => 'Thường',
                'gia' => 20000,
                'calo' => 180,
                'so_luong_ton' => 25,
                'trang_thai' => true,
                'mo_ta' => 'Chè ba màu thường',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Gỏi Cuốn Tôm Thịt - ma_san_pham = 7
            [
                'ma_bien_the' => 13,
                'ma_san_pham' => 7,
                'kich_thuoc' => '2 cuốn',
                'gia' => 25000,
                'calo' => 120,
                'so_luong_ton' => 20,
                'trang_thai' => true,
                'mo_ta' => 'Gỏi cuốn 2 cuốn',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 14,
                'ma_san_pham' => 7,
                'kich_thuoc' => '4 cuốn',
                'gia' => 45000,
                'calo' => 240,
                'so_luong_ton' => 15,
                'trang_thai' => true,
                'mo_ta' => 'Gỏi cuốn 4 cuốn',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            
            // Cà Phê Sữa Đá - ma_san_pham = 8
            [
                'ma_bien_the' => 15,
                'ma_san_pham' => 8,
                'kich_thuoc' => 'Nhỏ',
                'gia' => 15000,
                'calo' => 120,
                'so_luong_ton' => 40,
                'trang_thai' => true,
                'mo_ta' => 'Cà phê sữa đá nhỏ',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_bien_the' => 16,
                'ma_san_pham' => 8,
                'kich_thuoc' => 'Lớn',
                'gia' => 20000,
                'calo' => 180,
                'so_luong_ton' => 35,
                'trang_thai' => true,
                'mo_ta' => 'Cà phê sữa đá lớn',
                'hinh_anh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('bien_the_san_pham')->whereIn('ma_bien_the', range(1, 16))->delete();
    }
};