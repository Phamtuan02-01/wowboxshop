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
        Schema::create('chi_tiet_nguyen_lieu_gio_hang', function (Blueprint $table) {
            $table->id('ma_chi_tiet_nguyen_lieu_gio_hang');
            $table->unsignedBigInteger('ma_chi_tiet_gio_hang');
            $table->unsignedBigInteger('ma_nguyen_lieu');
            $table->integer('so_luong');
            $table->timestamps();

            $table->foreign('ma_chi_tiet_gio_hang')->references('ma_chi_tiet_gio_hang')->on('chi_tiet_gio_hang')->onDelete('cascade');
            $table->foreign('ma_nguyen_lieu')->references('ma_nguyen_lieu')->on('nguyen_lieu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_nguyen_lieu_gio_hang');
    }
};