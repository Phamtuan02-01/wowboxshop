<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KhuyenMai;
use App\Models\SanPham;
use App\Models\DanhMuc;
use App\Models\LichSuKhuyenMai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = KhuyenMai::query();

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active()->valid();
            } elseif ($request->status === 'inactive') {
                $query->where('trang_thai', false);
            } elseif ($request->status === 'expired') {
                $query->where('ngay_ket_thuc', '<', Carbon::now());
            } elseif ($request->status === 'upcoming') {
                $query->where('ngay_bat_dau', '>', Carbon::now());
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('loai_khuyen_mai', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_khuyen_mai', 'like', "%{$search}%")
                  ->orWhere('ma_code', 'like', "%{$search}%")
                  ->orWhere('mo_ta', 'like', "%{$search}%");
            });
        }

        $promotions = $query->latest()->paginate(10);

        // Statistics
        $stats = [
            'total' => KhuyenMai::count(),
            'active' => KhuyenMai::active()->valid()->count(),
            'expired' => KhuyenMai::where('ngay_ket_thuc', '<', Carbon::now())->count(),
            'upcoming' => KhuyenMai::where('ngay_bat_dau', '>', Carbon::now())->count(),
        ];

        return view('admin.promotions.index', compact('promotions', 'stats'));
    }

    public function create()
    {
        // Chỉ lấy sản phẩm loại 'product' và đang hoạt động
        $products = SanPham::where('trang_thai', true)
                          ->where('loai_san_pham', 'product')
                          ->get();
        
        // Chỉ lấy danh mục có chứa sản phẩm loại 'product'
        $categories = DanhMuc::whereHas('sanPhams', function($query) {
            $query->where('loai_san_pham', 'product');
        })->get();
        
        return view('admin.promotions.create', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_khuyen_mai' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'ma_code' => 'nullable|string|max:50|unique:khuyen_mai,ma_code',
            'loai_khuyen_mai' => 'required|in:percent,fixed,product_discount',
            'gia_tri' => 'required|numeric|min:0',
            'gia_tri_toi_da' => 'nullable|numeric|min:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'gioi_han_su_dung' => 'nullable|integer|min:1',
            'gioi_han_moi_khach' => 'required|integer|min:1',
            'ngay_bat_dau' => 'required|date|after_or_equal:today',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'ap_dung_tat_ca' => 'boolean',
            'san_pham_ap_dung' => 'nullable|array',
            'san_pham_ap_dung.*' => 'exists:san_pham,ma_san_pham',
            'danh_muc_ap_dung' => 'nullable|array',
            'danh_muc_ap_dung.*' => 'exists:danh_muc,ma_danh_muc',
            'trang_thai' => 'boolean'
        ]);

        // Generate code if not provided
        if (empty($validated['ma_code'])) {
            $validated['ma_code'] = 'PROMO' . strtoupper(Str::random(6));
        }

        // Handle application scope
        $validated['ap_dung_tat_ca'] = $request->has('ap_dung_tat_ca') ? true : false;
        $validated['trang_thai'] = $request->has('trang_thai') ? true : false;
        
        $sanPhamIds = [];
        $danhMucIds = [];
        
        if (!$validated['ap_dung_tat_ca']) {
            $sanPhamIds = $validated['san_pham_ap_dung'] ?? [];
            $danhMucIds = $validated['danh_muc_ap_dung'] ?? [];
        } else {
            $validated['san_pham_ap_dung'] = null;
            $validated['danh_muc_ap_dung'] = null;
        }

        $promotion = KhuyenMai::create($validated);
        
        // Sync vào bảng trung gian many-to-many
        if (!$validated['ap_dung_tat_ca']) {
            if (!empty($sanPhamIds)) {
                $promotion->sanPhams()->sync($sanPhamIds);
            }
            if (!empty($danhMucIds)) {
                $promotion->danhMucs()->sync($danhMucIds);
            }
        }

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Khuyến mãi đã được tạo thành công!');
    }

    public function show(KhuyenMai $promotion)
    {
        $promotion->load('lichSuKhuyenMai.taiKhoan', 'lichSuKhuyenMai.donHang');
        
        $usageStats = [
            'total_used' => $promotion->so_luong_su_dung,
            'total_discount' => $promotion->lichSuKhuyenMai->sum('gia_tri_giam'),
            'unique_users' => $promotion->lichSuKhuyenMai->pluck('ma_tai_khoan')->unique()->count(),
        ];

        return view('admin.promotions.show', compact('promotion', 'usageStats'));
    }

    public function edit(KhuyenMai $promotion)
    {
        // Chỉ lấy sản phẩm loại 'product' và đang hoạt động
        $products = SanPham::where('trang_thai', true)
                          ->where('loai_san_pham', 'product')
                          ->get();
        
        // Chỉ lấy danh mục có chứa sản phẩm loại 'product'
        $categories = DanhMuc::whereHas('sanPhams', function($query) {
            $query->where('loai_san_pham', 'product');
        })->get();
        
        return view('admin.promotions.edit', compact('promotion', 'products', 'categories'));
    }

    public function update(Request $request, KhuyenMai $promotion)
    {
        $validated = $request->validate([
            'ten_khuyen_mai' => 'required|string|max:255',
            'mo_ta' => 'nullable|string',
            'ma_code' => 'nullable|string|max:50|unique:khuyen_mai,ma_code,' . $promotion->ma_khuyen_mai . ',ma_khuyen_mai',
            'loai_khuyen_mai' => 'required|in:percent,fixed,product_discount',
            'gia_tri' => 'required|numeric|min:0',
            'gia_tri_toi_da' => 'nullable|numeric|min:0',
            'don_hang_toi_thieu' => 'nullable|numeric|min:0',
            'gioi_han_su_dung' => 'nullable|integer|min:1',
            'gioi_han_moi_khach' => 'required|integer|min:1',
            'ngay_bat_dau' => 'required|date',
            'ngay_ket_thuc' => 'required|date|after:ngay_bat_dau',
            'ap_dung_tat_ca' => 'boolean',
            'san_pham_ap_dung' => 'nullable|array',
            'san_pham_ap_dung.*' => 'exists:san_pham,ma_san_pham',
            'danh_muc_ap_dung' => 'nullable|array',
            'danh_muc_ap_dung.*' => 'exists:danh_muc,ma_danh_muc',
            'trang_thai' => 'boolean'
        ]);

        // Handle application scope
        $validated['ap_dung_tat_ca'] = $request->has('ap_dung_tat_ca') ? true : false;
        $validated['trang_thai'] = $request->has('trang_thai') ? true : false;
        
        $sanPhamIds = [];
        $danhMucIds = [];
        
        if (!$validated['ap_dung_tat_ca']) {
            $sanPhamIds = $validated['san_pham_ap_dung'] ?? [];
            $danhMucIds = $validated['danh_muc_ap_dung'] ?? [];
        } else {
            $validated['san_pham_ap_dung'] = null;
            $validated['danh_muc_ap_dung'] = null;
        }

        $promotion->update($validated);
        
        // Sync vào bảng trung gian many-to-many
        if (!$validated['ap_dung_tat_ca']) {
            if (!empty($sanPhamIds)) {
                $promotion->sanPhams()->sync($sanPhamIds);
            } else {
                $promotion->sanPhams()->detach();
            }
            
            if (!empty($danhMucIds)) {
                $promotion->danhMucs()->sync($danhMucIds);
            } else {
                $promotion->danhMucs()->detach();
            }
        } else {
            // Nếu áp dụng tất cả, xóa hết trong bảng trung gian
            $promotion->sanPhams()->detach();
            $promotion->danhMucs()->detach();
        }

        return redirect()->route('admin.promotions.index')
                        ->with('success', 'Khuyến mãi đã được cập nhật thành công!');
    }

    public function destroy(KhuyenMai $promotion)
    {
        try {
            \Log::info('Attempting to delete promotion', [
                'promotion_id' => $promotion->ma_khuyen_mai,
                'promotion_name' => $promotion->ten_khuyen_mai,
                'is_ajax' => request()->ajax()
            ]);

            // Delete image if exists
            if ($promotion->hinh_anh && file_exists(public_path('images/promotions/' . $promotion->hinh_anh))) {
                unlink(public_path('images/promotions/' . $promotion->hinh_anh));
                \Log::info('Deleted promotion image', ['image' => $promotion->hinh_anh]);
            }

            $promotionName = $promotion->ten_khuyen_mai;
            $promotion->delete();
            
            \Log::info('Promotion deleted successfully', ['promotion_name' => $promotionName]);

            // Check if this is an AJAX request
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Khuyến mãi '{$promotionName}' đã được xóa thành công!"
                ]);
            }

            return redirect()->route('admin.promotions.index')
                            ->with('success', "Khuyến mãi '{$promotionName}' đã được xóa thành công!");
        } catch (\Exception $e) {
            \Log::error('Error deleting promotion', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'promotion_id' => $promotion->ma_khuyen_mai ?? 'unknown'
            ]);

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi xóa khuyến mãi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->route('admin.promotions.index')
                            ->with('error', 'Có lỗi xảy ra khi xóa khuyến mãi!');
        }
    }

    public function toggleStatus(KhuyenMai $promotion)
    {
        try {
            \Log::info('Toggle status request', [
                'promotion_id' => $promotion->ma_khuyen_mai,
                'current_status' => $promotion->trang_thai
            ]);

            $newStatus = !$promotion->trang_thai;
            $promotion->update(['trang_thai' => $newStatus]);

            \Log::info('Toggle status success', [
                'promotion_id' => $promotion->ma_khuyen_mai,
                'new_status' => $newStatus
            ]);

            $status = $newStatus ? 'kích hoạt' : 'tắt';
            return response()->json([
                'success' => true,
                'message' => "Khuyến mãi đã được {$status} thành công!",
                'status' => $newStatus
            ]);
        } catch (\Exception $e) {
            \Log::error('Toggle status error', [
                'error' => $e->getMessage(),
                'promotion_id' => $promotion->ma_khuyen_mai ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'exists:khuyen_mai,ma_khuyen_mai'
        ]);

        $promotions = KhuyenMai::whereIn('ma_khuyen_mai', $validated['selected_items']);

        switch ($validated['action']) {
            case 'activate':
                $promotions->update(['trang_thai' => true]);
                $message = 'Các khuyến mãi đã được kích hoạt thành công!';
                break;
                
            case 'deactivate':
                $promotions->update(['trang_thai' => false]);
                $message = 'Các khuyến mãi đã được tắt thành công!';
                break;
                
            case 'delete':
                foreach ($promotions->get() as $promotion) {
                    if ($promotion->hinh_anh && file_exists(public_path('images/promotions/' . $promotion->hinh_anh))) {
                        unlink(public_path('images/promotions/' . $promotion->hinh_anh));
                    }
                }
                $promotions->delete();
                $message = 'Các khuyến mãi đã được xóa thành công!';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function checkCode(Request $request)
    {
        $code = $request->input('code');
        $orderValue = $request->input('order_value', 0); // Tổng giá trị đơn hàng
        
        $promotion = KhuyenMai::byCode($code)->first();

        if (!$promotion) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi không tồn tại!'
            ]);
        }

        // Kiểm tra trạng thái
        if (!$promotion->trang_thai) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi đã bị vô hiệu hóa!'
            ]);
        }

        // Kiểm tra thời gian
        $now = \Carbon\Carbon::now();
        if ($promotion->ngay_bat_dau > $now) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi chưa có hiệu lực!'
            ]);
        }

        if ($promotion->ngay_ket_thuc < $now) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi đã hết hạn!'
            ]);
        }

        // Kiểm tra giới hạn sử dụng tổng
        if ($promotion->gioi_han_su_dung && $promotion->so_luong_su_dung >= $promotion->gioi_han_su_dung) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi đã hết lượt sử dụng!'
            ]);
        }

        // Kiểm tra giới hạn sử dụng của khách hàng
        if (\Auth::check() && $promotion->gioi_han_moi_khach) {
            $userUsage = \App\Models\LichSuKhuyenMai::where('ma_khuyen_mai', $promotion->ma_khuyen_mai)
                                      ->where('ma_tai_khoan', \Auth::id())
                                      ->count();
            if ($userUsage >= $promotion->gioi_han_moi_khach) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Bạn đã sử dụng mã này ' . $userUsage . '/' . $promotion->gioi_han_moi_khach . ' lần. Không thể sử dụng thêm!'
                ]);
            }
        }

        // Kiểm tra giá trị đơn hàng tối thiểu
        if ($promotion->don_hang_toi_thieu && $orderValue < $promotion->don_hang_toi_thieu) {
            return response()->json([
                'valid' => false,
                'message' => 'Đơn hàng tối thiểu phải từ ' . number_format($promotion->don_hang_toi_thieu, 0, ',', '.') . '₫ để áp dụng mã này!'
            ]);
        }

        // Chỉ cho phép mã khuyến mãi loại 'fixed' (giảm giá cố định cho hóa đơn)
        if ($promotion->loai_khuyen_mai !== 'fixed') {
            return response()->json([
                'valid' => false,
                'message' => 'Chỉ áp dụng được mã giảm giá cố định cho hóa đơn!'
            ]);
        }

        return response()->json([
            'valid' => true,
            'promotion' => [
                'id' => $promotion->ma_khuyen_mai,
                'name' => $promotion->ten_khuyen_mai,
                'type' => $promotion->loai_khuyen_mai,
                'value' => $promotion->gia_tri,
                'max_value' => $promotion->gia_tri_toi_da,
                'min_order' => $promotion->don_hang_toi_thieu,
                'display' => $promotion->gia_tri_display
            ]
        ]);
    }
    
    /**
     * Get realtime status of promotions
     */
    public function getStatuses(Request $request)
    {
        $promotionIds = $request->input('ids', []);
        
        if (empty($promotionIds)) {
            return response()->json([]);
        }
        
        $promotions = KhuyenMai::whereIn('ma_khuyen_mai', $promotionIds)->get();
        
        $statuses = [];
        $now = Carbon::now();
        
        foreach ($promotions as $promotion) {
            $statuses[$promotion->ma_khuyen_mai] = [
                'status_text' => $promotion->trang_thai_text,
                'status_class' => $this->getStatusClass($promotion->trang_thai_text),
                // Debug info
                'debug' => [
                    'now' => $now->format('Y-m-d H:i:s'),
                    'start' => $promotion->ngay_bat_dau ? $promotion->ngay_bat_dau->format('Y-m-d H:i:s') : null,
                    'end' => $promotion->ngay_ket_thuc ? $promotion->ngay_ket_thuc->format('Y-m-d H:i:s') : null,
                    'timezone' => $now->timezoneName,
                    'trang_thai' => $promotion->trang_thai
                ]
            ];
        }
        
        return response()->json($statuses);
    }
    
    private function getStatusClass($statusText)
    {
        $classes = [
            'Đang hoạt động' => 'success',
            'Đã kết thúc' => 'danger',
            'Chưa bắt đầu' => 'warning',
            'Đã tắt' => 'secondary'
        ];
        
        return $classes[$statusText] ?? 'secondary';
    }
}
