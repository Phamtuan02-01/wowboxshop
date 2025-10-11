<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->decimal('gia', 10, 2)->default(0)->after('mo_ta');
            $table->decimal('gia_khuyen_mai', 10, 2)->nullable()->after('gia');
            $table->integer('so_luong_ton_kho')->default(0)->after('gia_khuyen_mai');
            $table->boolean('trang_thai')->default(true)->after('so_luong_ton_kho'); // true = đang bán, false = tạm ngưng
            $table->string('hinh_anh', 255)->nullable()->after('trang_thai'); // thay vì hinh_anh_url
            $table->text('hinh_anh_phu')->nullable()->after('hinh_anh'); // JSON array cho nhiều ảnh
            $table->string('thuong_hieu', 100)->nullable()->after('hinh_anh_phu');
            $table->string('xuat_xu', 100)->nullable()->after('thuong_hieu');
            $table->text('thong_tin_dinh_duong')->nullable()->after('xuat_xu');
            $table->integer('luot_xem')->default(0)->after('thong_tin_dinh_duong');
            $table->decimal('diem_danh_gia_trung_binh', 3, 2)->default(0)->after('luot_xem');
            $table->integer('so_luot_danh_gia')->default(0)->after('diem_danh_gia_trung_binh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->dropColumn([
                'gia',
                'gia_khuyen_mai', 
                'so_luong_ton_kho',
                'trang_thai',
                'hinh_anh',
                'hinh_anh_phu',
                'thuong_hieu',
                'xuat_xu',
                'thong_tin_dinh_duong',
                'luot_xem',
                'diem_danh_gia_trung_binh',
                'so_luot_danh_gia'
            ]);
        });
    }
};