<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khuyen_mai', function (Blueprint $table) {
            $table->id('ma_khuyen_mai');
            $table->string('ten_khuyen_mai', 255);
            $table->text('mo_ta')->nullable();
            $table->string('ma_code', 50)->unique()->nullable();
            $table->enum('loai_khuyen_mai', ['percent', 'fixed', 'product_discount']);
            $table->decimal('gia_tri', 10, 2);
            $table->decimal('gia_tri_toi_da', 10, 2)->nullable();
            $table->decimal('don_hang_toi_thieu', 10, 2)->default(0);
            $table->integer('so_luong_su_dung')->default(0);
            $table->integer('gioi_han_su_dung')->nullable();
            $table->integer('gioi_han_moi_khach')->default(1);
            $table->dateTime('ngay_bat_dau');
            $table->dateTime('ngay_ket_thuc');
            $table->boolean('trang_thai')->default(true);
            $table->json('san_pham_ap_dung')->nullable();
            $table->json('danh_muc_ap_dung')->nullable();
            $table->boolean('ap_dung_tat_ca')->default(false);
            $table->string('hinh_anh', 255)->nullable();
            $table->timestamps();

            $table->index(['trang_thai', 'ngay_bat_dau', 'ngay_ket_thuc']);
            $table->index('ma_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai');
    }
};
