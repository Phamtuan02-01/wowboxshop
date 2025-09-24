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
        Schema::create('san_pham', function (Blueprint $table) {
            $table->id('ma_san_pham');
            $table->string('ten_san_pham', 100);
            $table->unsignedBigInteger('ma_danh_muc');
            $table->text('mo_ta')->nullable();
            $table->string('hinh_anh_url', 255)->nullable();
            $table->boolean('la_noi_bat')->default(false);
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_danh_muc')->references('ma_danh_muc')->on('danh_muc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham');
    }
};