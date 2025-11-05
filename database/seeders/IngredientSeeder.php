<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SanPham;
use App\Models\BienTheSanPham;
use App\Models\DanhMuc;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo danh mục Nguyên liệu nếu chưa có
        $danhMucNguyenLieu = [
            ['ten_danh_muc' => 'Rau củ', 'ma_danh_muc_cha' => null],
            ['ten_danh_muc' => 'Thịt', 'ma_danh_muc_cha' => null],
            ['ten_danh_muc' => 'Hải sản', 'ma_danh_muc_cha' => null],
            ['ten_danh_muc' => 'Gia vị', 'ma_danh_muc_cha' => null],
            ['ten_danh_muc' => 'Đồ khô', 'ma_danh_muc_cha' => null],
        ];

        $danhMucIds = [];
        foreach ($danhMucNguyenLieu as $dm) {
            $category = DanhMuc::firstOrCreate(
                ['ten_danh_muc' => $dm['ten_danh_muc']],
                ['ma_danh_muc_cha' => $dm['ma_danh_muc_cha']]
            );
            $danhMucIds[$dm['ten_danh_muc']] = $category->ma_danh_muc;
        }

        // Dữ liệu nguyên liệu mẫu
        $ingredients = [
            // Rau củ
            [
                'ten_san_pham' => 'Cà chua',
                'ma_danh_muc' => $danhMucIds['Rau củ'],
                'mo_ta' => 'Cà chua tươi, giàu vitamin C và chất chống oxy hóa',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '500g', 'gia' => 20000, 'so_luong_ton' => 100],
                    ['kich_co' => '1kg', 'gia' => 35000, 'so_luong_ton' => 80],
                ]
            ],
            [
                'ten_san_pham' => 'Hành tây',
                'ma_danh_muc' => $danhMucIds['Rau củ'],
                'mo_ta' => 'Hành tây tươi, thơm ngon cho mọi món ăn',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '500g', 'gia' => 15000, 'so_luong_ton' => 150],
                    ['kich_co' => '1kg', 'gia' => 28000, 'so_luong_ton' => 100],
                ]
            ],
            [
                'ten_san_pham' => 'Khoai tây',
                'ma_danh_muc' => $danhMucIds['Rau củ'],
                'mo_ta' => 'Khoai tây Đà Lạt, mềm ngọt, giàu tinh bột',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'variants' => [
                    ['kich_co' => '500g', 'gia' => 18000, 'so_luong_ton' => 120],
                    ['kich_co' => '1kg', 'gia' => 32000, 'so_luong_ton' => 90],
                ]
            ],
            [
                'ten_san_pham' => 'Xà lách',
                'ma_danh_muc' => $danhMucIds['Rau củ'],
                'mo_ta' => 'Xà lách tươi, sạch, giòn ngon cho salad',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '200g', 'gia' => 12000, 'so_luong_ton' => 80],
                    ['kich_co' => '500g', 'gia' => 25000, 'so_luong_ton' => 60],
                ]
            ],

            // Thịt
            [
                'ten_san_pham' => 'Thịt bò Úc',
                'ma_danh_muc' => $danhMucIds['Thịt'],
                'mo_ta' => 'Thịt bò nhập khẩu từ Úc, mềm ngon, giàu protein',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'variants' => [
                    ['kich_co' => '300g', 'gia' => 120000, 'so_luong_ton' => 50],
                    ['kich_co' => '500g', 'gia' => 190000, 'so_luong_ton' => 40],
                ]
            ],
            [
                'ten_san_pham' => 'Thịt gà ta',
                'ma_danh_muc' => $danhMucIds['Thịt'],
                'mo_ta' => 'Thịt gà ta thả vườn, thơm ngon, không chất kích thích',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '500g', 'gia' => 80000, 'so_luong_ton' => 70],
                    ['kich_co' => '1kg', 'gia' => 150000, 'so_luong_ton' => 50],
                ]
            ],
            [
                'ten_san_pham' => 'Thịt heo ba chỉ',
                'ma_danh_muc' => $danhMucIds['Thịt'],
                'mo_ta' => 'Thịt ba chỉ tươi ngon, vừa nạc vừa mỡ',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '500g', 'gia' => 65000, 'so_luong_ton' => 90],
                    ['kich_co' => '1kg', 'gia' => 120000, 'so_luong_ton' => 60],
                ]
            ],

            // Hải sản
            [
                'ten_san_pham' => 'Tôm sú',
                'ma_danh_muc' => $danhMucIds['Hải sản'],
                'mo_ta' => 'Tôm sú tươi sống, to đầu, thịt chắc ngọt',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'variants' => [
                    ['kich_co' => '300g', 'gia' => 150000, 'so_luong_ton' => 40],
                    ['kich_co' => '500g', 'gia' => 240000, 'so_luong_ton' => 30],
                ]
            ],
            [
                'ten_san_pham' => 'Cá hồi Na Uy',
                'ma_danh_muc' => $danhMucIds['Hải sản'],
                'mo_ta' => 'Cá hồi nhập khẩu Na Uy, giàu Omega-3',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'variants' => [
                    ['kich_co' => '200g', 'gia' => 120000, 'so_luong_ton' => 35],
                    ['kich_co' => '500g', 'gia' => 280000, 'so_luong_ton' => 25],
                ]
            ],
            [
                'ten_san_pham' => 'Mực ống',
                'ma_danh_muc' => $danhMucIds['Hải sản'],
                'mo_ta' => 'Mực ống tươi, thịt trắng giòn ngọt',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '300g', 'gia' => 90000, 'so_luong_ton' => 50],
                    ['kich_co' => '500g', 'gia' => 140000, 'so_luong_ton' => 40],
                ]
            ],

            // Gia vị
            [
                'ten_san_pham' => 'Tỏi băm',
                'ma_danh_muc' => $danhMucIds['Gia vị'],
                'mo_ta' => 'Tỏi băm sẵn, tiện lợi, giữ nguyên hương vị',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '100g', 'gia' => 15000, 'so_luong_ton' => 100],
                    ['kich_co' => '200g', 'gia' => 28000, 'so_luong_ton' => 80],
                ]
            ],
            [
                'ten_san_pham' => 'Ớt bột Hàn Quốc',
                'ma_danh_muc' => $danhMucIds['Gia vị'],
                'mo_ta' => 'Ớt bột Hàn Quốc chính hãng, cay nồng thơm ngon',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '100g', 'gia' => 35000, 'so_luong_ton' => 60],
                    ['kich_co' => '500g', 'gia' => 150000, 'so_luong_ton' => 40],
                ]
            ],

            // Đồ khô
            [
                'ten_san_pham' => 'Nấm hương khô',
                'ma_danh_muc' => $danhMucIds['Đồ khô'],
                'mo_ta' => 'Nấm hương khô cao cấp, thơm nồng, giàu dinh dưỡng',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => false,
                'variants' => [
                    ['kich_co' => '100g', 'gia' => 45000, 'so_luong_ton' => 70],
                    ['kich_co' => '200g', 'gia' => 85000, 'so_luong_ton' => 50],
                ]
            ],
            [
                'ten_san_pham' => 'Mỳ Ý Barilla',
                'ma_danh_muc' => $danhMucIds['Đồ khô'],
                'mo_ta' => 'Mỳ Ý nhập khẩu chính hãng từ Italia',
                'loai_san_pham' => 'ingredient',
                'trang_thai' => true,
                'la_noi_bat' => true,
                'variants' => [
                    ['kich_co' => '500g', 'gia' => 55000, 'so_luong_ton' => 80],
                    ['kich_co' => '1kg', 'gia' => 100000, 'so_luong_ton' => 60],
                ]
            ],
        ];

        // Thêm nguyên liệu vào database
        foreach ($ingredients as $ingredientData) {
            $variants = $ingredientData['variants'];
            unset($ingredientData['variants']);

            // Tạo sản phẩm
            $sanPham = SanPham::create($ingredientData);

            // Tạo biến thể
            foreach ($variants as $variantData) {
                BienTheSanPham::create([
                    'ma_san_pham' => $sanPham->ma_san_pham,
                    'kich_thuoc' => $variantData['kich_co'],
                    'gia' => $variantData['gia'],
                    'calo' => 0, // Mặc định 0, có thể cập nhật sau
                    'so_luong_ton' => $variantData['so_luong_ton'],
                    'trang_thai' => true,
                ]);
            }

            echo "✓ Đã tạo nguyên liệu: {$sanPham->ten_san_pham}\n";
        }

        echo "\n✅ Hoàn thành! Đã tạo " . count($ingredients) . " nguyên liệu mẫu.\n";
    }
}
