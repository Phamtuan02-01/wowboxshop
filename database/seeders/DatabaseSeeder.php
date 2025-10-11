<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            VaiTroSeeder::class,
            DanhMucSeeder::class,
            TaiKhoanSeeder::class,
            DiaChiSeeder::class,
            UpdateDiaChiMacDinhSeeder::class,
            SanPhamSeeder::class,
            BienTheSanPhamSeeder::class,
            BaiVietBlogSeeder::class,
            DanhGiaSeeder::class,
            GioHangSeeder::class,
            ChiTietGioHangSeeder::class,
        ]);
    }
}
