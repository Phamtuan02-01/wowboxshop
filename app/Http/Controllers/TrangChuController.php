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
        // Cách đơn giản hơn: lấy sản phẩm có biến thể có giá cao nhất (giả định là sản phẩm nổi bật)
        $sanPhamNoiBat = SanPham::with(['bienThes' => function($query) {
                $query->orderBy('gia', 'DESC');
            }])
            ->limit(5)
            ->get();

        // Nếu không đủ sản phẩm, lấy tất cả sản phẩm có
        if ($sanPhamNoiBat->count() < 5) {
            $sanPhamNoiBat = SanPham::with('bienThes')->get();
        }

        return view('trangchu', compact('sanPhamNoiBat'));
    }
}