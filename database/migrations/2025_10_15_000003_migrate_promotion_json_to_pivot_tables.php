<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Di chuyển dữ liệu từ JSON fields sang bảng trung gian
     *
     * @return void
     */
    public function up()
    {
        // Lấy tất cả khuyến mãi
        $khuyenMais = DB::table('khuyen_mai')->get();
        
        foreach ($khuyenMais as $khuyenMai) {
            // Xử lý sản phẩm
            if ($khuyenMai->san_pham_ap_dung) {
                $sanPhamIds = json_decode($khuyenMai->san_pham_ap_dung, true);
                
                if (is_array($sanPhamIds) && !empty($sanPhamIds)) {
                    foreach ($sanPhamIds as $sanPhamId) {
                        // Kiểm tra xem đã tồn tại chưa
                        $exists = DB::table('khuyen_mai_san_pham')
                                    ->where('ma_khuyen_mai', $khuyenMai->ma_khuyen_mai)
                                    ->where('ma_san_pham', $sanPhamId)
                                    ->exists();
                        
                        if (!$exists) {
                            DB::table('khuyen_mai_san_pham')->insert([
                                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                                'ma_san_pham' => $sanPhamId,
                                'ngay_them' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
            
            // Xử lý danh mục
            if ($khuyenMai->danh_muc_ap_dung) {
                $danhMucIds = json_decode($khuyenMai->danh_muc_ap_dung, true);
                
                if (is_array($danhMucIds) && !empty($danhMucIds)) {
                    foreach ($danhMucIds as $danhMucId) {
                        // Kiểm tra xem đã tồn tại chưa
                        $exists = DB::table('khuyen_mai_danh_muc')
                                    ->where('ma_khuyen_mai', $khuyenMai->ma_khuyen_mai)
                                    ->where('ma_danh_muc', $danhMucId)
                                    ->exists();
                        
                        if (!$exists) {
                            DB::table('khuyen_mai_danh_muc')->insert([
                                'ma_khuyen_mai' => $khuyenMai->ma_khuyen_mai,
                                'ma_danh_muc' => $danhMucId,
                                'ngay_them' => now(),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
        
        echo "Migrated promotion data from JSON to pivot tables successfully!\n";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Không cần rollback vì dữ liệu JSON vẫn còn
        echo "Rollback not needed - JSON data is preserved.\n";
    }
};
