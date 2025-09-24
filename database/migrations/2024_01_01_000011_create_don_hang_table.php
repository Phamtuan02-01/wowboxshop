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
        Schema::create('don_hang', function (Blueprint $table) {
            $table->id('ma_don_hang');
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->timestamp('ngay_dat_hang')->useCurrent();
            $table->string('trang_thai', 50);
            $table->decimal('tong_tien', 18, 2);
            $table->unsignedBigInteger('ma_dia_chi');
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoan');
            $table->foreign('ma_dia_chi')->references('ma_dia_chi')->on('dia_chi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('don_hang');
    }
};