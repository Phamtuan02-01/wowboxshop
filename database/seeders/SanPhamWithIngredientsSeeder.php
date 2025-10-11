<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\DanhMuc;

class SanPhamWithIngredientsSeeder extends Seeder
{
    public function run()
    {
        // Lấy categories cho ingredients (từ migration đã tạo)
        $rauCuCategory = DanhMuc::where('ten_danh_muc', 'Rau củ')->first();
        $traiCayCategory = DanhMuc::where('ten_danh_muc', 'Trái cây')->first();
        $proteinCategory = DanhMuc::where('ten_danh_muc', 'Protein')->first();
        
        // Tạo một số sản phẩm thông thường
        $saladBowlCategory = DanhMuc::where('ten_danh_muc', 'Salad Bowl')->first();
        $smoothieCategory = DanhMuc::where('ten_danh_muc', 'Smoothie')->first();
        
        // Tạo sản phẩm thông thường
        if ($saladBowlCategory) {
            SanPham::create([
                'ten_san_pham' => 'Salad Caesar',
                'ma_danh_muc' => $saladBowlCategory->ma_danh_muc,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Salad Caesar truyền thống với xà lách tươi, phô mai parmesan và sốt Caesar',
                'gia' => 125000,
                'so_luong_ton_kho' => 50,
                'trang_thai' => true,
                'la_noi_bat' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);
        }
        
        if ($smoothieCategory) {
            SanPham::create([
                'ten_san_pham' => 'Smoothie Tropical',
                'ma_danh_muc' => $smoothieCategory->ma_danh_muc,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Smoothie nhiệt đới với xoài, dứa và chuối',
                'gia' => 85000,
                'so_luong_ton_kho' => 30,
                'trang_thai' => true,
                'la_noi_bat' => false,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);
        }
        
        // Tạo nguyên liệu (ingredients)
        if ($rauCuCategory) {
            SanPham::create([
                'ten_san_pham' => 'Xà lách',
                'ma_danh_muc' => $rauCuCategory->ma_danh_muc,
                'loai_san_pham' => 'ingredient',
                'mo_ta' => 'Xà lách tươi hữu cơ',
                'gia' => 15000,
                'khoi_luong_tinh' => 100,
                'calo' => 15,
                'so_luong_ton_kho' => 200,
                'trang_thai' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);

            SanPham::create([
                'ten_san_pham' => 'Cà rốt',
                'ma_danh_muc' => $rauCuCategory->ma_danh_muc,
                'loai_san_pham' => 'ingredient',
                'mo_ta' => 'Cà rốt tươi ngọt',
                'gia' => 12000,
                'khoi_luong_tinh' => 100,
                'calo' => 41,
                'so_luong_ton_kho' => 150,
                'trang_thai' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);
        }

        if ($traiCayCategory) {
            SanPham::create([
                'ten_san_pham' => 'Chuối',
                'ma_danh_muc' => $traiCayCategory->ma_danh_muc,
                'loai_san_pham' => 'ingredient',
                'mo_ta' => 'Chuối chín ngọt',
                'gia' => 20000,
                'khoi_luong_tinh' => 100,
                'calo' => 89,
                'so_luong_ton_kho' => 100,
                'trang_thai' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);

            SanPham::create([
                'ten_san_pham' => 'Dâu tây',
                'ma_danh_muc' => $traiCayCategory->ma_danh_muc,
                'loai_san_pham' => 'ingredient',
                'mo_ta' => 'Dâu tây tươi ngon',
                'gia' => 45000,
                'khoi_luong_tinh' => 100,
                'calo' => 32,
                'so_luong_ton_kho' => 80,
                'trang_thai' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);
        }

        if ($proteinCategory) {
            SanPham::create([
                'ten_san_pham' => 'Ức gà',
                'ma_danh_muc' => $proteinCategory->ma_danh_muc,
                'loai_san_pham' => 'ingredient',
                'mo_ta' => 'Ức gà nướng không da',
                'gia' => 55000,
                'khoi_luong_tinh' => 100,
                'calo' => 165,
                'so_luong_ton_kho' => 60,
                'trang_thai' => true,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);
        }
    }
}