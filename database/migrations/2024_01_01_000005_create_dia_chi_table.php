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
        Schema::create('dia_chi', function (Blueprint $table) {
            $table->id('ma_dia_chi');
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->string('ho_ten', 100);
            $table->string('thanh_pho', 50);
            $table->string('quan_huyen', 50);
            $table->string('dia_chi_chi_tiet', 200);
            $table->string('so_dien_thoai', 20);
            $table->boolean('la_mac_dinh')->default(false);
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dia_chi');
    }
};