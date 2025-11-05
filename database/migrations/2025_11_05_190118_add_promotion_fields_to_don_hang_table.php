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
            $table->unsignedBigInteger('ma_khuyen_mai')->nullable()->after('tong_tien');
            $table->decimal('giam_gia_khuyen_mai', 18, 2)->default(0)->after('ma_khuyen_mai');
            
            $table->foreign('ma_khuyen_mai')->references('ma_khuyen_mai')->on('khuyen_mai')->onDelete('set null');
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
            $table->dropForeign(['ma_khuyen_mai']);
            $table->dropColumn(['ma_khuyen_mai', 'giam_gia_khuyen_mai']);
        });
    }
};
