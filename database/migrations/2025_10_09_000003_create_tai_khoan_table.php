<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tai_khoan', function (Blueprint $table) {
            $table->id('ma_tai_khoan');
            $table->string('ten_dang_nhap', 50)->unique();
            $table->string('email', 100)->unique();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 20);
            $table->binary('mat_khau_hash');
            $table->unsignedBigInteger('ma_vai_tro');
            $table->unsignedBigInteger('ma_dia_chi_mac_dinh')->nullable();
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes('deleted_at');

            $table->foreign('ma_vai_tro')->references('ma_vai_tro')->on('vai_tro');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tai_khoan');
    }
};
