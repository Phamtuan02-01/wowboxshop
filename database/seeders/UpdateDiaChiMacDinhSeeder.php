<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDiaChiMacDinhSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cập nhật địa chỉ mặc định cho các tài khoản
        DB::table('tai_khoan')->where('ma_tai_khoan', 1)->update(['ma_dia_chi_mac_dinh' => 1]); // Admin
        DB::table('tai_khoan')->where('ma_tai_khoan', 2)->update(['ma_dia_chi_mac_dinh' => 2]); // Manager
        DB::table('tai_khoan')->where('ma_tai_khoan', 3)->update(['ma_dia_chi_mac_dinh' => 3]); // Customer01
        DB::table('tai_khoan')->where('ma_tai_khoan', 4)->update(['ma_dia_chi_mac_dinh' => 5]); // Customer02
        DB::table('tai_khoan')->where('ma_tai_khoan', 5)->update(['ma_dia_chi_mac_dinh' => 6]); // Customer03
    }
}