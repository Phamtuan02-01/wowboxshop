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
            // Drop foreign key constraint
            $table->dropForeign(['ma_dia_chi']);
            // Make ma_dia_chi nullable
            $table->unsignedBigInteger('ma_dia_chi')->nullable()->change();
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
            // Re-add foreign key constraint
            $table->unsignedBigInteger('ma_dia_chi')->nullable(false)->change();
            $table->foreign('ma_dia_chi')->references('ma_dia_chi')->on('dia_chi');
        });
    }
};
