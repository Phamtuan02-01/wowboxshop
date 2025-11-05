<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng trung gian quản lý mối quan hệ many-to-many giữa khuyến mãi và sản phẩm
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khuyen_mai_san_pham', function (Blueprint $table) {
            $table->id('ma_khuyen_mai_san_pham');
            $table->unsignedBigInteger('ma_khuyen_mai');
            $table->unsignedBigInteger('ma_san_pham');
            $table->decimal('gia_tri_giam_cu_the', 10, 2)->nullable(); // Giá trị giảm riêng cho sản phẩm này (nếu có)
            $table->integer('so_lan_ap_dung')->default(0); // Đếm số lần khuyến mãi được áp dụng cho sản phẩm này
            $table->datetime('ngay_them')->useCurrent(); // Ngày thêm sản phẩm vào khuyến mãi
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('ma_khuyen_mai')
                  ->references('ma_khuyen_mai')
                  ->on('khuyen_mai')
                  ->onDelete('cascade');
                  
            $table->foreign('ma_san_pham')
                  ->references('ma_san_pham')
                  ->on('san_pham')
                  ->onDelete('cascade');
            
            // Unique constraint để tránh trùng lặp
            $table->unique(['ma_khuyen_mai', 'ma_san_pham'], 'khuyen_mai_san_pham_unique');
            
            // Indexes cho query performance
            $table->index('ma_khuyen_mai');
            $table->index('ma_san_pham');
            $table->index('ngay_them');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khuyen_mai_san_pham');
    }
};
