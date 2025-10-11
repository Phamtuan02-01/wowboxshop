<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use App\Models\SanPham;
use App\Models\BienTheSanPham;
use App\Helpers\FlashMessage;
use Illuminate\Support\Facades\Auth;

class GioHangController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        if (!Auth::check()) {
            FlashMessage::warning('Vui lòng đăng nhập để xem giỏ hàng');
            return redirect()->route('dangnhap');
        }

        $taiKhoan = Auth::user();
        
        // Tìm hoặc tạo giỏ hàng cho tài khoản
        $gioHang = GioHang::firstOrCreate(
            ['ma_tai_khoan' => $taiKhoan->ma_tai_khoan]
        );

        // Lấy chi tiết giỏ hàng với thông tin sản phẩm
        $chiTietGioHang = ChiTietGioHang::with(['sanPham', 'bienThe'])
            ->where('ma_gio_hang', $gioHang->ma_gio_hang)
            ->get();

        // Tính tổng tiền
        $tongTien = 0;
        $phiVanChuyen = 10000; // Phí vận chuyển cố định
        
        foreach ($chiTietGioHang as $item) {
            $gia = $item->bienThe ? $item->bienThe->gia : 0;
            $tongTien += $gia * $item->so_luong;
        }

        $tongHoaDon = $tongTien + $phiVanChuyen;

        return view('giohang.index', compact('chiTietGioHang', 'tongTien', 'phiVanChuyen', 'tongHoaDon'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function themSanPham(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Vui lòng đăng nhập']);
        }

        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'ma_bien_the' => 'required|exists:bien_the_san_pham,ma_bien_the',
            'so_luong' => 'required|integer|min:1'
        ]);

        $taiKhoan = Auth::user();
        
        // Tìm hoặc tạo giỏ hàng
        $gioHang = GioHang::firstOrCreate(
            ['ma_tai_khoan' => $taiKhoan->ma_tai_khoan]
        );

        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        $chiTietGioHang = ChiTietGioHang::where([
            'ma_gio_hang' => $gioHang->ma_gio_hang,
            'ma_san_pham' => $request->ma_san_pham,
            'ma_bien_the' => $request->ma_bien_the
        ])->first();

        if ($chiTietGioHang) {
            // Nếu đã có, tăng số lượng
            $chiTietGioHang->so_luong += $request->so_luong;
            $chiTietGioHang->save();
        } else {
            // Nếu chưa có, tạo mới
            ChiTietGioHang::create([
                'ma_gio_hang' => $gioHang->ma_gio_hang,
                'ma_san_pham' => $request->ma_san_pham,
                'ma_bien_the' => $request->ma_bien_the,
                'so_luong' => $request->so_luong
            ]);
        }

        FlashMessage::success('Đã thêm sản phẩm vào giỏ hàng!');
        return response()->json(['success' => true, 'message' => 'Đã thêm vào giỏ hàng']);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function capNhatSoLuong(Request $request, $id)
    {
        $chiTietGioHang = ChiTietGioHang::find($id);
        
        if (!$chiTietGioHang) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
        }

        $soLuong = max(1, intval($request->so_luong));
        $chiTietGioHang->so_luong = $soLuong;
        $chiTietGioHang->save();

        // Tính lại tổng tiền cho item này
        $gia = $chiTietGioHang->bienThe ? $chiTietGioHang->bienThe->gia : 0;
        $tongTienItem = $gia * $soLuong;

        return response()->json([
            'success' => true,
            'tong_tien_item' => number_format($tongTienItem, 0, ',', '.') . ' VND'
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function xoaSanPham($id)
    {
        $chiTietGioHang = ChiTietGioHang::find($id);
        
        if (!$chiTietGioHang) {
            FlashMessage::error('Không tìm thấy sản phẩm');
            return back();
        }

        $chiTietGioHang->delete();
        FlashMessage::success('Đã xóa sản phẩm khỏi giỏ hàng');
        
        return back();
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng (AJAX)
     */
    public function xoaSanPhamAjax($id)
    {
        $chiTietGioHang = ChiTietGioHang::find($id);
        
        if (!$chiTietGioHang) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
        }

        $chiTietGioHang->delete();
        
        return response()->json(['success' => true, 'message' => 'Đã xóa sản phẩm khỏi giỏ hàng']);
    }

    /**
     * Đếm số lượng sản phẩm trong giỏ hàng
     */
    public function demSanPham()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }

        $gioHang = GioHang::where('ma_tai_khoan', Auth::user()->ma_tai_khoan)->first();
        
        if (!$gioHang) {
            return response()->json(['count' => 0]);
        }

        $count = ChiTietGioHang::where('ma_gio_hang', $gioHang->ma_gio_hang)->sum('so_luong');
        
        return response()->json(['count' => $count]);
    }
}