<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TaiKhoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tai_khoan')->insert([
            [
                'ten_dang_nhap' => 'admin',
                'email' => 'admin@wowboxshop.com',
                'so_dien_thoai' => '0123456789',
                'mat_khau_hash' => Hash::make('123456'),
                'ma_vai_tro' => 1, // Admin
                'ma_dia_chi_mac_dinh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_dang_nhap' => 'quanly1',
                'email' => 'quanly@wowboxshop.com',
                'so_dien_thoai' => '0987654321',
                'mat_khau_hash' => Hash::make('123456'),
                'ma_vai_tro' => 3, // Quan ly
                'ma_dia_chi_mac_dinh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_dang_nhap' => 'khachhang1',
                'email' => 'nguyenvana@gmail.com',
                'so_dien_thoai' => '0901234567',
                'mat_khau_hash' => Hash::make('123456'),
                'ma_vai_tro' => 2, // Khach hang
                'ma_dia_chi_mac_dinh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_dang_nhap' => 'khachhang2',
                'email' => 'tranthib@gmail.com',
                'so_dien_thoai' => '0912345678',
                'mat_khau_hash' => Hash::make('123456'),
                'ma_vai_tro' => 2, // Khach hang
                'ma_dia_chi_mac_dinh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ],
            [
                'ten_dang_nhap' => 'khachhang3',
                'email' => 'lethic@gmail.com',
                'so_dien_thoai' => '0923456789',
                'mat_khau_hash' => Hash::make('123456'),
                'ma_vai_tro' => 2, // Khach hang
                'ma_dia_chi_mac_dinh' => null,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now()
            ]
        ]);
    }
}