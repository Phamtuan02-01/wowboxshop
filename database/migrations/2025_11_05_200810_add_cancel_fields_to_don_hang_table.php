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
            $table->text('ly_do_huy')->nullable()->after('ghi_chu');
            $table->timestamp('ngay_huy')->nullable()->after('ly_do_huy');
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
            $table->dropColumn(['ly_do_huy', 'ngay_huy']);
        });
    }
};
