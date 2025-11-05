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
        Schema::table('don_hang', function (Blueprint $table) {
            $table->enum('phuong_thuc_giao_hang', ['giao_hang', 'nhan_tai_cua_hang'])->default('giao_hang')->after('tong_tien');
            $table->enum('phuong_thuc_thanh_toan', ['momo', 'cod'])->default('momo')->after('phuong_thuc_giao_hang');
            $table->string('ho_ten')->nullable()->after('phuong_thuc_thanh_toan');
            $table->string('so_dien_thoai', 15)->nullable()->after('ho_ten');
            $table->text('dia_chi')->nullable()->after('so_dien_thoai');
            $table->string('tinh_thanh_pho')->nullable()->after('dia_chi');
            $table->text('cua_hang_nhan')->nullable()->after('tinh_thanh_pho');
            $table->text('ghi_chu')->nullable()->after('cua_hang_nhan');
            $table->string('ma_thanh_toan')->nullable()->after('ghi_chu');
            $table->text('url_thanh_toan')->nullable()->after('ma_thanh_toan');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('don_hang', function (Blueprint $table) {
            $table->dropColumn([
                'phuong_thuc_giao_hang',
                'phuong_thuc_thanh_toan',
                'ho_ten',
                'so_dien_thoai',
                'dia_chi',
                'tinh_thanh_pho',
                'cua_hang_nhan',
                'ghi_chu',
                'ma_thanh_toan',
                'url_thanh_toan'
            ]);
        });
    }
};
