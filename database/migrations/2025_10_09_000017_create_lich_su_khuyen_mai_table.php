<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_khuyen_mai', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ma_khuyen_mai');
            $table->unsignedBigInteger('ma_don_hang');
            $table->unsignedBigInteger('ma_tai_khoan');
            $table->decimal('gia_tri_giam', 10, 2);
            $table->string('ma_code_su_dung', 50)->nullable();
            $table->dateTime('ngay_su_dung');
            $table->timestamps();

            $table->foreign('ma_khuyen_mai')->references('ma_khuyen_mai')->on('khuyen_mai')->onDelete('cascade');
            $table->foreign('ma_don_hang')->references('ma_don_hang')->on('don_hang')->onDelete('cascade');
            $table->foreign('ma_tai_khoan')->references('ma_tai_khoan')->on('tai_khoan')->onDelete('cascade');
            
            $table->index(['ma_khuyen_mai', 'ma_tai_khoan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_khuyen_mai');
    }
};
