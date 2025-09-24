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
        Schema::create('chi_tiet_gio_hang', function (Blueprint $table) {
            $table->id('ma_chi_tiet_gio_hang');
            $table->unsignedBigInteger('ma_gio_hang');
            $table->unsignedBigInteger('ma_san_pham');
            $table->unsignedBigInteger('ma_bien_the')->nullable();
            $table->integer('so_luong');
            $table->timestamps();

            $table->foreign('ma_gio_hang')->references('ma_gio_hang')->on('gio_hang')->onDelete('cascade');
            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_pham');
            $table->foreign('ma_bien_the')->references('ma_bien_the')->on('bien_the_san_pham');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_gio_hang');
    }
};