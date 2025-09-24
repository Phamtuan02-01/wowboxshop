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
        Schema::create('danh_muc', function (Blueprint $table) {
            $table->id('ma_danh_muc');
            $table->string('ten_danh_muc', 50);
            $table->unsignedBigInteger('ma_danh_muc_cha')->nullable();
            $table->timestamps();

            $table->foreign('ma_danh_muc_cha')->references('ma_danh_muc')->on('danh_muc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('danh_muc');
    }
};