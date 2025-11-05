<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use Illuminate\Support\Facades\Session;

class TuChonController extends Controller
{
    /**
     * Hiển thị trang tự chọn nguyên liệu
     */
    public function index(Request $request)
    {
        // Lấy danh mục được chọn
        $selectedCategory = $request->get('danh_muc');
        
        // Query sản phẩm nguyên liệu
        $query = SanPham::where('loai_san_pham', 'ingredient')
                        ->where('trang_thai', true)
                        ->whereHas('bienThes', function($q) {
                            $q->where('trang_thai', true)->where('so_luong_ton', '>', 0);
                        })
                        ->with(['danhMuc', 'bienThes' => function($q) {
                            $q->where('trang_thai', true)->where('so_luong_ton', '>', 0);
                        }]);
        
        // Lọc theo danh mục nếu có
        if ($selectedCategory) {
            $query->where('ma_danh_muc', $selectedCategory);
        }
        
        $sanPhams = $query->orderBy('ten_san_pham', 'asc')->get();
        
        // Lấy danh sách các danh mục có sản phẩm ingredient
        $danhMucIds = $sanPhams->pluck('ma_danh_muc')->unique()->filter();
        $danhMuc = DanhMuc::whereIn('ma_danh_muc', $danhMucIds)
                          ->orderBy('ten_danh_muc', 'asc')
                          ->get();
        
        // Nhóm sản phẩm theo danh mục
        $sanPhamTheoDanhMuc = $sanPhams->groupBy('ma_danh_muc');
        
        // Lấy giỏ tự chọn từ session
        $gioTuChon = Session::get('gio_tu_chon', []);
        
        // Tính tổng tiền
        $tongTien = 0;
        foreach ($gioTuChon as $item) {
            $tongTien += $item['gia'] * $item['so_luong'];
        }
        
        return view('tu-chon.index', compact('danhMuc', 'sanPhamTheoDanhMuc', 'selectedCategory', 'gioTuChon', 'tongTien'));
    }
    
    /**
     * Thêm/cập nhật sản phẩm vào giỏ tự chọn (session)
     */
    public function updateSession(Request $request)
    {
        $request->validate([
            'ma_bien_the' => 'required|exists:bien_the_san_pham,ma_bien_the',
            'so_luong' => 'required|integer|min:0'
        ]);
        
        $maBienThe = $request->ma_bien_the;
        $soLuong = $request->so_luong;
        
        // Lấy thông tin biến thể
        $bienThe = \App\Models\BienTheSanPham::with('sanPham')->find($maBienThe);
        
        if (!$bienThe || !$bienThe->trang_thai) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có sẵn'
            ]);
        }
        
        if ($soLuong > $bienThe->so_luong_ton) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá tồn kho'
            ]);
        }
        
        // Lấy giỏ tự chọn từ session
        $gioTuChon = Session::get('gio_tu_chon', []);
        
        if ($soLuong > 0) {
            // Thêm hoặc cập nhật
            $gioTuChon[$maBienThe] = [
                'ma_bien_the' => $maBienThe,
                'ma_san_pham' => $bienThe->ma_san_pham,
                'ten_san_pham' => $bienThe->sanPham->ten_san_pham,
                'kich_co' => $bienThe->kich_thuoc ?? 'Mặc định',
                'gia' => $bienThe->gia,
                'so_luong' => $soLuong,
                'hinh_anh' => $bienThe->sanPham->hinh_anh
            ];
        } else {
            // Xóa nếu số lượng = 0
            unset($gioTuChon[$maBienThe]);
        }
        
        Session::put('gio_tu_chon', $gioTuChon);
        
        // Tính tổng tiền
        $tongTien = 0;
        $tongSoLuong = 0;
        foreach ($gioTuChon as $item) {
            $tongTien += $item['gia'] * $item['so_luong'];
            $tongSoLuong += $item['so_luong'];
        }
        
        return response()->json([
            'success' => true,
            'message' => $soLuong > 0 ? 'Đã cập nhật giỏ tự chọn' : 'Đã xóa khỏi giỏ tự chọn',
            'cart' => $gioTuChon,
            'tong_tien' => $tongTien,
            'tong_so_luong' => $tongSoLuong
        ]);
    }
    
    /**
     * Thêm tất cả sản phẩm từ giỏ tự chọn vào giỏ hàng chính
     */
    public function addToCart(Request $request)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm vào giỏ hàng'
            ]);
        }
        
        $gioTuChon = Session::get('gio_tu_chon', []);
        
        if (empty($gioTuChon)) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ tự chọn đang trống'
            ]);
        }
        
        try {
            \DB::beginTransaction();
            
            // Tìm hoặc tạo giỏ hàng
            $gioHang = \App\Models\GioHang::firstOrCreate([
                'ma_tai_khoan' => auth()->id()
            ]);
            
            $addedCount = 0;
            foreach ($gioTuChon as $item) {
                // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
                $chiTietGioHang = \App\Models\ChiTietGioHang::where('ma_gio_hang', $gioHang->ma_gio_hang)
                                              ->where('ma_san_pham', $item['ma_san_pham'])
                                              ->where('ma_bien_the', $item['ma_bien_the'])
                                              ->first();
                
                if ($chiTietGioHang) {
                    // Cập nhật số lượng
                    $chiTietGioHang->increment('so_luong', $item['so_luong']);
                } else {
                    // Thêm mới
                    \App\Models\ChiTietGioHang::create([
                        'ma_gio_hang' => $gioHang->ma_gio_hang,
                        'ma_san_pham' => $item['ma_san_pham'],
                        'ma_bien_the' => $item['ma_bien_the'],
                        'so_luong' => $item['so_luong']
                    ]);
                }
                $addedCount++;
            }
            
            \DB::commit();
            
            // Xóa giỏ tự chọn sau khi thêm vào giỏ hàng
            Session::forget('gio_tu_chon');
            
            return response()->json([
                'success' => true,
                'message' => "Đã thêm {$addedCount} sản phẩm vào giỏ hàng",
                'redirect' => route('giohang')
            ]);
            
        } catch (\Exception $e) {
            \DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Xóa toàn bộ giỏ tự chọn
     */
    public function clearSession()
    {
        Session::forget('gio_tu_chon');
        
        return response()->json([
            'success' => true,
            'message' => 'Đã xóa giỏ tự chọn'
        ]);
    }
}
