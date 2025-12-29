<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bien_the_san_pham', function (Blueprint $table) {
            $table->id('ma_bien_the');
            $table->unsignedBigInteger('ma_san_pham');
            $table->string('kich_thuoc', 20)->nullable();
            $table->decimal('gia', 18, 2);
            $table->integer('calo');
            $table->timestamp('ngay_tao')->nullable();
            $table->timestamp('ngay_cap_nhat')->nullable();
            $table->boolean('trang_thai')->default(true);
            $table->integer('so_luong_ton')->default(0);
            $table->timestamps();

            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_pham')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bien_the_san_pham');
    }
};
