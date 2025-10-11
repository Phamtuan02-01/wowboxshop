<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VaiTroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('vai_tro')->insert([
            ['ten_vai_tro' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['ten_vai_tro' => 'Khach hang', 'created_at' => now(), 'updated_at' => now()],
            ['ten_vai_tro' => 'Quan ly', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}