<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\DanhMuc;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tạo các danh mục con cho Đồ Uống
        $doUong = DanhMuc::where('ten_danh_muc', 'Đồ Uống')->first();
        if ($doUong) {
            DanhMuc::firstOrCreate([
                'ten_danh_muc' => 'Nước Ngọt',
                'ma_danh_muc_cha' => $doUong->ma_danh_muc
            ]);
            
            DanhMuc::firstOrCreate([
                'ten_danh_muc' => 'Cà Phê',
                'ma_danh_muc_cha' => $doUong->ma_danh_muc
            ]);
            
            DanhMuc::firstOrCreate([
                'ten_danh_muc' => 'Trà',
                'ma_danh_muc_cha' => $doUong->ma_danh_muc
            ]);
        }
        
        // Tạo các danh mục con cho Thức Ăn
        $thucAn = DanhMuc::where('ten_danh_muc', 'Thức Ăn')->first();
        if ($thucAn) {
            DanhMuc::firstOrCreate([
                'ten_danh_muc' => 'Món Chính',
                'ma_danh_muc_cha' => $thucAn->ma_danh_muc
            ]);
            
            DanhMuc::firstOrCreate([
                'ten_danh_muc' => 'Tráng Miệng',
                'ma_danh_muc_cha' => $thucAn->ma_danh_muc
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa các danh mục con
        DanhMuc::whereIn('ten_danh_muc', [
            'Nước Ngọt', 'Cà Phê', 'Trà', 'Món Chính', 'Tráng Miệng'
        ])->delete();
    }
};