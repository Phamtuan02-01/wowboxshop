<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id('ma_san_pham');
            $table->string('ten_san_pham', 100);
            $table->unsignedBigInteger('ma_danh_muc');
            $table->enum('loai_san_pham', ['product', 'ingredient'])->default('product');
            $table->text('mo_ta')->nullable();
            $table->decimal('gia', 10, 2)->default(0);
            $table->decimal('gia_khuyen_mai', 10, 2)->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->string('hinh_anh', 255)->nullable();
            $table->text('hinh_anh_phu')->nullable();
            $table->string('thuong_hieu', 100)->nullable();
            $table->string('xuat_xu', 100)->nullable();
            $table->integer('luot_xem')->default(0);
            $table->decimal('diem_danh_gia_trung_binh', 3, 2)->default(0);
            $table->integer('so_luot_danh_gia')->default(0);
            $table->string('hinh_anh_url', 255)->nullable();
            $table->boolean('la_noi_bat')->default(false);
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_danh_muc')->references('ma_danh_muc')->on('danh_muc');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};
