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
        Schema::create('nguyen_lieu', function (Blueprint $table) {
            $table->id('ma_nguyen_lieu');
            $table->string('ten_nguyen_lieu', 100);
            $table->unsignedBigInteger('ma_nhom_nguyen_lieu');
            $table->string('hinh_anh_url', 255)->nullable();
            $table->decimal('gia', 18, 2);
            $table->decimal('khoi_luong_tinh', 10, 2)->nullable();
            $table->integer('calo')->nullable();
            $table->timestamp('ngay_tao')->useCurrent();
            $table->timestamp('ngay_cap_nhat')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('ma_nhom_nguyen_lieu')->references('ma_nhom_nguyen_lieu')->on('nhom_nguyen_lieu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguyen_lieu');
    }
};