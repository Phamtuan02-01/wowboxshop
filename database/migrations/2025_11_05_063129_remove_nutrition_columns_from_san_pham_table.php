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
        Schema::table('san_pham', function (Blueprint $table) {
            $table->dropColumn(['thong_tin_dinh_duong', 'khoi_luong_tinh', 'calo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('san_pham', function (Blueprint $table) {
            $table->text('thong_tin_dinh_duong')->nullable()->after('mo_ta');
            $table->decimal('khoi_luong_tinh', 8, 2)->nullable()->after('thong_tin_dinh_duong');
            $table->integer('calo')->nullable()->after('khoi_luong_tinh');
        });
    }
};
