<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\ChiTietDonHang;
use Illuminate\Support\Facades\DB;

class TrangChuController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function index()
    {
        // Lấy sản phẩm nổi bật (chỉ sản phẩm thường, không lấy nguyên liệu)
        // Lọc theo cột la_noi_bat = true, loai_san_pham = 'product', trang_thai = true
        $sanPhamNoiBat = SanPham::with(['bienThes' => function($query) {
                $query->orderBy('gia', 'ASC');
            }])
            ->where('la_noi_bat', true)
            ->where('loai_san_pham', 'product')
            ->where('trang_thai', true)
            ->limit(5)
            ->get();

        return view('trangchu', compact('sanPhamNoiBat'));
    }
}