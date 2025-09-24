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
            NhomNguyenLieuSeeder::class,
            TaiKhoanSeeder::class,
            DiaChiSeeder::class,
            UpdateDiaChiMacDinhSeeder::class,
            NguyenLieuSeeder::class,
            SanPhamSeeder::class,
            SanPhamNguyenLieuSeeder::class,
            BienTheSanPhamSeeder::class,
            BaiVietBlogSeeder::class,
            DanhGiaSeeder::class,
            GioHangSeeder::class,
            ChiTietGioHangSeeder::class,
            ChiTietNguyenLieuGioHangSeeder::class,
        ]);
    }
}
