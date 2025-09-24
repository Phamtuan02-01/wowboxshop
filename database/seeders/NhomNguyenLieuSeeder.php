<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NhomNguyenLieuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('nhom_nguyen_lieu')->insert([
            ['ten_nhom' => 'Rau củ', 'created_at' => now(), 'updated_at' => now()],
            ['ten_nhom' => 'Trái cây', 'created_at' => now(), 'updated_at' => now()],
            ['ten_nhom' => 'Protein', 'created_at' => now(), 'updated_at' => now()],
            ['ten_nhom' => 'Hạt và Seed', 'created_at' => now(), 'updated_at' => now()],
            ['ten_nhom' => 'Ngũ cốc', 'created_at' => now(), 'updated_at' => now()],
            ['ten_nhom' => 'Gia vị', 'created_at' => now(), 'updated_at' => now()],
            ['ten_nhom' => 'Sữa và chế phẩm', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}