<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id('ma_don_hang');
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->timestamp('ngay_dat_hang')->useCurrent();
            $table->string('trang_thai', 50);
            $table->decimal('tong_tien', 18, 2);
            $table->unsignedBigInteger('ma_khuyen_mai')->nullable();
            $table->decimal('giam_gia_khuyen_mai', 18, 2)->default(0);
            $table->enum('phuong_thuc_giao_hang', ['giao_hang', 'nhan_tai_cua_hang'])->default('giao_hang');
            $table->enum('phuong_thuc_thanh_toan', ['momo', 'cod'])->default('momo');
            $table->string('ho_ten', 255)->nullable();
            $table->string('so_dien_thoai', 15)->nullable();
            $table->text('dia_chi')->nullable();
            $table->string('tinh_thanh_pho', 255)->nullable();
            $table->text('cua_hang_nhan')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->text('ly_do_huy')->nullable();
            $table->timestamp('ngay_huy')->nullable();
            $table->string('ma_thanh_toan', 255)->nullable();
            $table->text('url_thanh_toan')->nullable();
            $table->unsignedBigInteger('ma_dia_chi')->nullable();
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoan');
            $table->foreign('ma_dia_chi')->references('ma_dia_chi')->on('dia_chi');
            $table->foreign('ma_khuyen_mai')->references('ma_khuyen_mai')->on('khuyen_mai')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};
