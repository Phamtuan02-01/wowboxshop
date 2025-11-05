<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\KhuyenMai;
use App\Models\GioHang;
use App\Models\ChiTietGioHang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DatMonController extends Controller
{
    /**
     * Hiển thị trang đặt món
     */
    public function index(Request $request)
    {
        // Lấy các khuyến mãi đang hoạt động để optimize query
        $activePromotions = KhuyenMai::active()->valid()->get();
        
        // Query sản phẩm cơ bản
        $query = SanPham::where('loai_san_pham', 'product')
                        ->where('trang_thai', true)
                        ->whereHas('bienThes', function($q) {
                            $q->where('trang_thai', true)->where('so_luong_ton', '>', 0);
                        })
                        ->with(['danhMuc', 'bienThes' => function($q) {
                            $q->where('trang_thai', true)->where('so_luong_ton', '>', 0);
                        }]);
        
        // Lọc theo danh mục
        if ($request->has('danh_muc') && $request->danh_muc != '') {
            $query->where('ma_danh_muc', $request->danh_muc);
        }
        
        // Tìm kiếm theo tên
        if ($request->has('search') && $request->search != '') {
            $query->where('ten_san_pham', 'LIKE', '%' . $request->search . '%');
        }
        
        // Lọc theo giá
        if ($request->has('gia_min') && $request->gia_min != '') {
            $query->where('gia', '>=', $request->gia_min);
        }
        
        if ($request->has('gia_max') && $request->gia_max != '') {
            $query->where('gia', '<=', $request->gia_max);
        }
        
        // Sắp xếp
        $sort = $request->get('sort', 'moi_nhat');
        switch ($sort) {
            case 'gia_thap':
                $query->orderBy('gia', 'asc');
                break;
            case 'gia_cao':
                $query->orderBy('gia', 'desc');
                break;
            case 'ten_az':
                $query->orderBy('ten_san_pham', 'asc');
                break;
            case 'ten_za':
                $query->orderBy('ten_san_pham', 'desc');
                break;
            case 'noi_bat':
                $query->orderBy('la_noi_bat', 'desc');
                break;
            case 'danh_gia':
                $query->orderBy('diem_danh_gia_trung_binh', 'desc');
                break;
            case 'ban_chay':
                $query->orderBy('luot_xem', 'desc');
                break;
            default: // moi_nhat
                $query->orderBy('ngay_tao', 'desc');
        }
        
        // Phân trang
        $sanPhams = $query->paginate(12)->appends($request->all());
        
        // Lấy sản phẩm nổi bật (lọc theo danh mục nếu có)
        $sanPhamNoiBatQuery = SanPham::where('loai_san_pham', 'product')
                                ->where('trang_thai', true)
                                ->where('la_noi_bat', true)
                                ->whereHas('bienThes', function($q) {
                                    $q->where('trang_thai', true)->where('so_luong_ton', '>', 0);
                                });
        
        // Lọc theo danh mục nếu có
        if ($request->has('danh_muc') && $request->danh_muc != '') {
            $sanPhamNoiBatQuery->where('ma_danh_muc', $request->danh_muc);
        }
        
        $sanPhamNoiBat = $sanPhamNoiBatQuery->with(['danhMuc', 'bienThes' => function($q) {
                                    $q->where('trang_thai', true)->where('so_luong_ton', '>', 0);
                                }])
                                ->take(6)
                                ->get();
        
        // Lấy danh sách danh mục có sản phẩm loại 'product'
        $danhMucIds = SanPham::where('loai_san_pham', 'product')
                             ->where('trang_thai', true)
                             ->pluck('ma_danh_muc')
                             ->unique()
                             ->filter();
        
        $danhMucs = DanhMuc::whereIn('ma_danh_muc', $danhMucIds)
                           ->orderBy('ten_danh_muc', 'asc')
                           ->get();
        
        // Lấy các khuyến mãi đang hoạt động để hiển thị
        $activePromotions = KhuyenMai::active()->valid()->get();
        
        return view('dat-mon.index', compact('sanPhams', 'danhMucs', 'sanPhamNoiBat', 'activePromotions'));
    }
    
    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'ma_bien_the' => 'required|exists:bien_the_san_pham,ma_bien_the',
            'so_luong' => 'required|integer|min:1'
        ]);
        
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng',
                'redirect' => route('dangnhap')
            ]);
        }
        
        $bienThe = \App\Models\BienTheSanPham::with('sanPham')->find($request->ma_bien_the);
        
        if (!$bienThe || !$bienThe->trang_thai) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không có sẵn'
            ]);
        }
        
        if ($bienThe->so_luong_ton < $request->so_luong) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không đủ số lượng trong kho'
            ]);
        }
        
        try {
            DB::beginTransaction();
            
            // Tìm hoặc tạo giỏ hàng
            $gioHang = GioHang::firstOrCreate([
                'ma_tai_khoan' => Auth::id()
            ]);
            
            // Kiểm tra sản phẩm đã có trong giỏ hàng chưa
            $chiTietGioHang = ChiTietGioHang::where('ma_gio_hang', $gioHang->ma_gio_hang)
                                          ->where('ma_san_pham', $request->ma_san_pham)
                                          ->where('ma_bien_the', $request->ma_bien_the)
                                          ->first();
            
            if ($chiTietGioHang) {
                // Cập nhật số lượng
                $soLuongMoi = $chiTietGioHang->so_luong + $request->so_luong;
                
                if ($soLuongMoi > $bienThe->so_luong_ton) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tổng số lượng vượt quá số lượng trong kho'
                    ]);
                }
                
                $chiTietGioHang->update([
                    'so_luong' => $soLuongMoi
                ]);
            } else {
                // Thêm mới
                ChiTietGioHang::create([
                    'ma_gio_hang' => $gioHang->ma_gio_hang,
                    'ma_san_pham' => $request->ma_san_pham,
                    'ma_bien_the' => $request->ma_bien_the,
                    'so_luong' => $request->so_luong
                ]);
            }
            
            DB::commit();
            
            // Đếm số lượng sản phẩm trong giỏ hàng
            $soLuongGioHang = ChiTietGioHang::where('ma_gio_hang', $gioHang->ma_gio_hang)->sum('so_luong');
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'cart_count' => $soLuongGioHang,
                'reload' => true // Báo hiệu cần reload trang để cập nhật giỏ hàng
            ]);
            
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra, vui lòng thử lại: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function chitiet($id)
    {
        $sanPham = SanPham::with(['danhMuc', 'bienThes' => function($query) {
            $query->where('so_luong_ton', '>', 0);
        }])->findOrFail($id);
        
        // Load promotion info (sử dụng accessor đã định nghĩa trong Model)
        $promotionInfo = $sanPham->promotion_info;
        $priceRange = $sanPham->price_range;

        return view('dat-mon.chi-tiet', compact('sanPham'));
    }
}