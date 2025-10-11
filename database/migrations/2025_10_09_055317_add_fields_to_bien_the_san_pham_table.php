<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bien_the_san_pham', function (Blueprint $table) {
            // Check if columns don't exist before adding
            if (!Schema::hasColumn('bien_the_san_pham', 'so_luong_ton')) {
                $table->integer('so_luong_ton')->default(0)->after('calo');
            }
            if (!Schema::hasColumn('bien_the_san_pham', 'trang_thai')) {
                $table->boolean('trang_thai')->default(true)->after('calo');
            }
            if (!Schema::hasColumn('bien_the_san_pham', 'mo_ta')) {
                $table->text('mo_ta')->nullable()->after('calo');
            }
            if (!Schema::hasColumn('bien_the_san_pham', 'hinh_anh')) {
                $table->string('hinh_anh')->nullable()->after('calo');
            }
            
            // Add new timestamp columns
            if (!Schema::hasColumn('bien_the_san_pham', 'ngay_tao')) {
                $table->timestamp('ngay_tao')->nullable()->after('hinh_anh');
            }
            if (!Schema::hasColumn('bien_the_san_pham', 'ngay_cap_nhat')) {
                $table->timestamp('ngay_cap_nhat')->nullable()->after('ngay_tao');
            }
        });
        
        // Copy data from old timestamp columns to new ones
        DB::statement('UPDATE bien_the_san_pham SET ngay_tao = created_at, ngay_cap_nhat = updated_at WHERE ngay_tao IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bien_the_san_pham', function (Blueprint $table) {
            $table->dropColumn(['so_luong_ton', 'trang_thai', 'mo_ta', 'hinh_anh']);
            
            // Rename back to original column names
            $table->renameColumn('ngay_tao', 'created_at');
            $table->renameColumn('ngay_cap_nhat', 'updated_at');
        });
    }
};
