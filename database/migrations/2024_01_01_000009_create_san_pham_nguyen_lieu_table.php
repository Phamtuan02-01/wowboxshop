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
        Schema::create('san_pham_nguyen_lieu', function (Blueprint $table) {
            $table->unsignedBigInteger('ma_san_pham');
            $table->unsignedBigInteger('ma_nguyen_lieu');
            $table->decimal('so_luong', 10, 2);
            $table->timestamps();

            $table->primary(['ma_san_pham', 'ma_nguyen_lieu']);
            $table->foreign('ma_san_pham')->references('ma_san_pham')->on('san_pham')->onDelete('cascade');
            $table->foreign('ma_nguyen_lieu')->references('ma_nguyen_lieu')->on('nguyen_lieu')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('san_pham_nguyen_lieu');
    }
};