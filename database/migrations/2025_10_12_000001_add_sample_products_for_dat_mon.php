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
        // Insert sample categories only if they don't exist
        $categories = [
            ['ma_danh_muc' => 1, 'ten_danh_muc' => 'Món chính'],
            ['ma_danh_muc' => 2, 'ten_danh_muc' => 'Món ăn vặt'],
            ['ma_danh_muc' => 3, 'ten_danh_muc' => 'Thức uống'],
            ['ma_danh_muc' => 4, 'ten_danh_muc' => 'Tráng miệng']
        ];

        foreach ($categories as $category) {
            DB::table('danh_muc')->updateOrInsert(
                ['ma_danh_muc' => $category['ma_danh_muc']],
                [
                    'ten_danh_muc' => $category['ten_danh_muc'],
                    'ma_danh_muc_cha' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }

        // Insert sample products only if they don't exist
        $products = [
            [
                'ma_san_pham' => 1,
                'ten_san_pham' => 'Phở Bò Tái',
                'ma_danh_muc' => 1,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Phở bò tái truyền thống với nước dùng đậm đà, thịt bò tươi ngon',
                'gia' => 65000,
                'gia_khuyen_mai' => 55000,
                'so_luong_ton_kho' => 50,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 500,
                'calo' => 350,
                'hinh_anh' => 'pho-bo-tai.jpg',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'luot_xem' => 150,
                'diem_danh_gia_trung_binh' => 4.5,
                'so_luot_danh_gia' => 25,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 2,
                'ten_san_pham' => 'Bánh Mì Thịt Nướng',
                'ma_danh_muc' => 2,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Bánh mì thịt nướng với thịt heo nướng thơm lừng, rau tươi',
                'gia' => 25000,
                'gia_khuyen_mai' => null,
                'so_luong_ton_kho' => 30,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 200,
                'calo' => 280,
                'hinh_anh' => 'banh-mi-thit-nuong.jpg',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'luot_xem' => 89,
                'diem_danh_gia_trung_binh' => 4.2,
                'so_luot_danh_gia' => 18,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 3,
                'ten_san_pham' => 'Cơm Tấm Sườn Nướng',
                'ma_danh_muc' => 1,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Cơm tấm với sườn nướng ngọt, trứng ốp la và chả',
                'gia' => 45000,
                'gia_khuyen_mai' => 40000,
                'so_luong_ton_kho' => 25,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 400,
                'calo' => 420,
                'hinh_anh' => 'com-tam-suon-nuong.jpg',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'luot_xem' => 120,
                'diem_danh_gia_trung_binh' => 4.7,
                'so_luot_danh_gia' => 30,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 4,
                'ten_san_pham' => 'Bún Bò Huế',
                'ma_danh_muc' => 1,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Bún bò Huế cay nồng, đậm đà hương vị miền Trung',
                'gia' => 50000,
                'gia_khuyen_mai' => null,
                'so_luong_ton_kho' => 20,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 450,
                'calo' => 380,
                'hinh_anh' => 'bun-bo-hue.jpg',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'luot_xem' => 95,
                'diem_danh_gia_trung_binh' => 4.3,
                'so_luot_danh_gia' => 22,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 5,
                'ten_san_pham' => 'Trà Sữa Truyền Thống',
                'ma_danh_muc' => 3,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Trà sữa truyền thống với hạt trân châu dai ngon',
                'gia' => 35000,
                'gia_khuyen_mai' => 30000,
                'so_luong_ton_kho' => 40,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 500,
                'calo' => 250,
                'hinh_anh' => 'tra-sua-truyen-thong.jpg',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'luot_xem' => 180,
                'diem_danh_gia_trung_binh' => 4.6,
                'so_luot_danh_gia' => 45,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 6,
                'ten_san_pham' => 'Chè Ba Màu',
                'ma_danh_muc' => 4,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Chè ba màu mát lạnh với đậu xanh, đậu đỏ và thạch',
                'gia' => 20000,
                'gia_khuyen_mai' => null,
                'so_luong_ton_kho' => 35,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 300,
                'calo' => 180,
                'hinh_anh' => 'che-ba-mau.jpg',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'luot_xem' => 65,
                'diem_danh_gia_trung_binh' => 4.1,
                'so_luot_danh_gia' => 15,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 7,
                'ten_san_pham' => 'Gỏi Cuốn Tôm Thịt',
                'ma_danh_muc' => 2,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Gỏi cuốn tươi ngon với tôm, thịt và rau thơm',
                'gia' => 30000,
                'gia_khuyen_mai' => 25000,
                'so_luong_ton_kho' => 15,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 150,
                'calo' => 120,
                'hinh_anh' => 'goi-cuon-tom-thit.jpg',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'luot_xem' => 75,
                'diem_danh_gia_trung_binh' => 4.4,
                'so_luot_danh_gia' => 20,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ma_san_pham' => 8,
                'ten_san_pham' => 'Cà Phê Sữa Đá',
                'ma_danh_muc' => 3,
                'loai_san_pham' => 'product',
                'mo_ta' => 'Cà phê sữa đá đậm đà, thơm ngon theo phong cách Việt Nam',
                'gia' => 18000,
                'gia_khuyen_mai' => null,
                'so_luong_ton_kho' => 60,
                'thuong_hieu' => 'WowBox',
                'xuat_xu' => 'Việt Nam',
                'khoi_luong_tinh' => 200,
                'calo' => 150,
                'hinh_anh' => 'ca-phe-sua-da.jpg',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'luot_xem' => 110,
                'diem_danh_gia_trung_binh' => 4.0,
                'so_luot_danh_gia' => 35,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ]
        ];

        foreach ($products as $product) {
            DB::table('san_pham')->updateOrInsert(
                ['ma_san_pham' => $product['ma_san_pham']],
                $product
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('san_pham')->whereIn('ma_san_pham', [1, 2, 3, 4, 5, 6, 7, 8])->delete();
        DB::table('danh_muc')->whereIn('ma_danh_muc', [1, 2, 3, 4])->delete();
    }
};