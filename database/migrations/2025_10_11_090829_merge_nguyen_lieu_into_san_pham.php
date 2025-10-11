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
        // Step 1: Add new columns to san_pham table
        Schema::table('san_pham', function (Blueprint $table) {
            $table->enum('loai_san_pham', ['product', 'ingredient'])->default('product')->after('ma_danh_muc');
            $table->integer('khoi_luong_tinh')->nullable()->after('thong_tin_dinh_duong');
            $table->integer('calo')->nullable()->after('khoi_luong_tinh');
        });

        // Step 2: Migrate nhom_nguyen_lieu to danh_muc
        $nhomNguyenLieus = DB::table('nhom_nguyen_lieu')->get();
        $nhomMapping = [];
        
        foreach ($nhomNguyenLieus as $nhom) {
            $danhMucId = DB::table('danh_muc')->insertGetId([
                'ten_danh_muc' => $nhom->ten_nhom,
                'ma_danh_muc_cha' => null,
                'created_at' => $nhom->ngay_tao ?? now(),
                'updated_at' => $nhom->ngay_cap_nhat ?? now(),
            ]);
            $nhomMapping[$nhom->ma_nhom_nguyen_lieu] = $danhMucId;
        }

        // Step 3: Migrate nguyen_lieu to san_pham
        $nguyenLieus = DB::table('nguyen_lieu')->get();
        $nguyenLieuMapping = [];
        
        foreach ($nguyenLieus as $nguyenLieu) {
            $sanPhamId = DB::table('san_pham')->insertGetId([
                'ten_san_pham' => $nguyenLieu->ten_nguyen_lieu,
                'ma_danh_muc' => $nhomMapping[$nguyenLieu->ma_nhom_nguyen_lieu] ?? null,
                'loai_san_pham' => 'ingredient',
                'mo_ta' => 'Nguyên liệu: ' . $nguyenLieu->ten_nguyen_lieu,
                'gia' => $nguyenLieu->gia,
                'gia_khuyen_mai' => null,
                'so_luong_ton_kho' => 1000, // Default stock for ingredients
                'trang_thai' => true,
                'hinh_anh' => $nguyenLieu->hinh_anh_url,
                'hinh_anh_phu' => null,
                'thuong_hieu' => null,
                'xuat_xu' => null,
                'thong_tin_dinh_duong' => null,
                'khoi_luong_tinh' => $nguyenLieu->khoi_luong_tinh,
                'calo' => $nguyenLieu->calo,
                'luot_xem' => 0,
                'diem_danh_gia_trung_binh' => 0,
                'so_luot_danh_gia' => 0,
                'hinh_anh_url' => $nguyenLieu->hinh_anh_url,
                'la_noi_bat' => false,
                'ngay_tao' => $nguyenLieu->ngay_tao ?? now(),
                'ngay_cap_nhat' => $nguyenLieu->ngay_cap_nhat ?? now(),
            ]);
            $nguyenLieuMapping[$nguyenLieu->ma_nguyen_lieu] = $sanPhamId;
        }

        // Step 4: Update chi_tiet_nguyen_lieu_gio_hang references
        $chiTietNguyenLieuGioHangs = DB::table('chi_tiet_nguyen_lieu_gio_hang')->get();
        foreach ($chiTietNguyenLieuGioHangs as $chiTiet) {
            if (isset($nguyenLieuMapping[$chiTiet->ma_nguyen_lieu])) {
                // Check if this product already exists in cart
                $existing = DB::table('chi_tiet_gio_hang')
                    ->where('ma_gio_hang', $chiTiet->ma_gio_hang)
                    ->where('ma_san_pham', $nguyenLieuMapping[$chiTiet->ma_nguyen_lieu])
                    ->first();
                
                if ($existing) {
                    // Update quantity
                    DB::table('chi_tiet_gio_hang')
                        ->where('ma_chi_tiet_gio_hang', $existing->ma_chi_tiet_gio_hang)
                        ->increment('so_luong', $chiTiet->so_luong);
                } else {
                    // Create new cart item
                    DB::table('chi_tiet_gio_hang')->insert([
                        'ma_gio_hang' => $chiTiet->ma_gio_hang,
                        'ma_san_pham' => $nguyenLieuMapping[$chiTiet->ma_nguyen_lieu],
                        'ma_bien_the' => null,
                        'so_luong' => $chiTiet->so_luong,
                        'gia_tai_thoi_diem_dat' => DB::table('san_pham')->where('ma_san_pham', $nguyenLieuMapping[$chiTiet->ma_nguyen_lieu])->value('gia'),
                        'ngay_tao' => now(),
                        'ngay_cap_nhat' => now(),
                    ]);
                }
            }
        }

        // Step 5: Update chi_tiet_nguyen_lieu_don_hang references  
        $chiTietNguyenLieuDonHangs = DB::table('chi_tiet_nguyen_lieu_don_hang')->get();
        foreach ($chiTietNguyenLieuDonHangs as $chiTiet) {
            if (isset($nguyenLieuMapping[$chiTiet->ma_nguyen_lieu])) {
                // Check if this product already exists in order
                $existing = DB::table('chi_tiet_don_hang')
                    ->where('ma_don_hang', $chiTiet->ma_don_hang)
                    ->where('ma_san_pham', $nguyenLieuMapping[$chiTiet->ma_nguyen_lieu])
                    ->first();
                
                if ($existing) {
                    // Update quantity
                    DB::table('chi_tiet_don_hang')
                        ->where('ma_chi_tiet_don_hang', $existing->ma_chi_tiet_don_hang)
                        ->increment('so_luong', $chiTiet->so_luong);
                } else {
                    // Create new order item
                    DB::table('chi_tiet_don_hang')->insert([
                        'ma_don_hang' => $chiTiet->ma_don_hang,
                        'ma_san_pham' => $nguyenLieuMapping[$chiTiet->ma_nguyen_lieu],
                        'ma_bien_the' => null,
                        'so_luong' => $chiTiet->so_luong,
                        'gia_tai_thoi_diem_dat' => $chiTiet->gia_tai_thoi_diem_dat,
                        'ngay_tao' => now(),
                        'ngay_cap_nhat' => now(),
                    ]);
                }
            }
        }

        // Step 6: Drop old tables
        Schema::dropIfExists('chi_tiet_nguyen_lieu_don_hang');
        Schema::dropIfExists('chi_tiet_nguyen_lieu_gio_hang');
        Schema::dropIfExists('san_pham_nguyen_lieu');
        Schema::dropIfExists('nguyen_lieu');
        Schema::dropIfExists('nhom_nguyen_lieu');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // This migration is not easily reversible due to data transformation
        // You would need to backup your data before running this migration
        throw new Exception('This migration cannot be reversed. Please restore from backup if needed.');
    }
};
