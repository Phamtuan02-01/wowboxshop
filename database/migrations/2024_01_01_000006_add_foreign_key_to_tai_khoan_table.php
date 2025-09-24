<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tai_khoan', function (Blueprint $table) {
            $table->foreign('ma_dia_chi_mac_dinh')->references('ma_dia_chi')->on('dia_chi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tai_khoan', function (Blueprint $table) {
            $table->dropForeign(['ma_dia_chi_mac_dinh']);
        });
    }
};