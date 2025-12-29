<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_gia', function (Blueprint $table) {
            $table->id('ma_danh_gia');
            $table->unsignedBigInteger('ma_san_pham');
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->integer('sao');
            $table->text('binh_luan')->nullable();
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_pham')->onDelete('cascade');
            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_gia');
    }
};
