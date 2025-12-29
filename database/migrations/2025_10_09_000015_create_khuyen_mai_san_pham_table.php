<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khuyen_mai_san_pham', function (Blueprint $table) {
            $table->id('ma_khuyen_mai_san_pham');
            $table->unsignedBigInteger('ma_khuyen_mai');
            $table->unsignedBigInteger('ma_san_pham');
            $table->decimal('gia_tri_giam_cu_the', 10, 2)->nullable();
            $table->integer('so_lan_ap_dung')->default(0);
            $table->dateTime('ngay_them')->useCurrent();
            $table->timestamps();

            $table->foreign('ma_khuyen_mai')->references('ma_khuyen_mai')->on('khuyen_mai')->onDelete('cascade');
            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_pham')->onDelete('cascade');
            
            $table->unique(['ma_khuyen_mai', 'ma_san_pham'], 'khuyen_mai_san_pham_unique');
            $table->index('ma_khuyen_mai');
            $table->index('ma_san_pham');
            $table->index('ngay_them');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khuyen_mai_san_pham');
    }
};
