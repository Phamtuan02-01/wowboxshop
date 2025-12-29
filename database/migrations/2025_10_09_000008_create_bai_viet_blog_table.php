<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bai_viet_blog', function (Blueprint $table) {
            $table->id('ma_bai_viet');
            $table->string('tieu_de', 200);
            $table->string('slug', 255)->unique();
            $table->text('noi_dung');
            $table->string('hinh_anh_url', 255)->nullable();
            $table->timestamp('ngay_dang')->useCurrent();
            $table->integer('luot_xem')->default(0);
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bai_viet_blog');
    }
};
