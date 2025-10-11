<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('khuyen_mai', function (Blueprint $table) {
            $table->id('ma_khuyen_mai');
            $table->string('ten_khuyen_mai', 255);
            $table->text('mo_ta')->nullable();
            $table->string('ma_code', 50)->unique()->nullable(); // Mã khuyến mãi để nhập
            $table->enum('loai_khuyen_mai', ['percent', 'fixed', 'product_discount']); // Loại: %, số tiền cố định, giảm giá sản phẩm
            $table->decimal('gia_tri', 10, 2); // Giá trị giảm (% hoặc số tiền)
            $table->decimal('gia_tri_toi_da', 10, 2)->nullable(); // Giá trị giảm tối đa (cho trường hợp %)
            $table->decimal('don_hang_toi_thieu', 10, 2)->default(0); // Giá trị đơn hàng tối thiểu
            $table->integer('so_luong_su_dung')->default(0); // Số lượng đã sử dụng
            $table->integer('gioi_han_su_dung')->nullable(); // Giới hạn số lần sử dụng
            $table->integer('gioi_han_moi_khach')->default(1); // Giới hạn mỗi khách hàng
            $table->datetime('ngay_bat_dau');
            $table->datetime('ngay_ket_thuc');
            $table->boolean('trang_thai')->default(true); // 1: hoạt động, 0: không hoạt động
            $table->json('san_pham_ap_dung')->nullable(); // JSON chứa ID sản phẩm áp dụng
            $table->json('danh_muc_ap_dung')->nullable(); // JSON chứa ID danh mục áp dụng
            $table->boolean('ap_dung_tat_ca')->default(false); // Áp dụng tất cả sản phẩm
            $table->string('hinh_anh')->nullable(); // Hình ảnh banner khuyến mãi
            $table->timestamps();
            
            $table->index(['trang_thai', 'ngay_bat_dau', 'ngay_ket_thuc']);
            $table->index('ma_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('khuyen_mai');
    }
};
