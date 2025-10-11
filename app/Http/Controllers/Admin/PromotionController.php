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
        $products = SanPham::where('trang_thai', true)->get();
        $categories = DanhMuc::all(); // Bỏ điều kiện trang_thai vì bảng danh_muc không có cột này
        
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
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'boolean'
        ]);

        // Generate code if not provided
        if (empty($validated['ma_code'])) {
            $validated['ma_code'] = 'PROMO' . strtoupper(Str::random(6));
        }

        // Handle image upload
        if ($request->hasFile('hinh_anh')) {
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/promotions'), $imageName);
            $validated['hinh_anh'] = $imageName;
        }

        // Handle application scope
        $validated['ap_dung_tat_ca'] = $request->has('ap_dung_tat_ca') ? true : false;
        $validated['trang_thai'] = $request->has('trang_thai') ? true : false;
        if ($validated['ap_dung_tat_ca']) {
            $validated['san_pham_ap_dung'] = null;
            $validated['danh_muc_ap_dung'] = null;
        }

        $promotion = KhuyenMai::create($validated);

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
        $products = SanPham::where('trang_thai', true)->get();
        $categories = DanhMuc::all(); // Bỏ điều kiện trang_thai vì bảng danh_muc không có cột này
        
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
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('hinh_anh')) {
            // Delete old image
            if ($promotion->hinh_anh && file_exists(public_path('images/promotions/' . $promotion->hinh_anh))) {
                unlink(public_path('images/promotions/' . $promotion->hinh_anh));
            }
            
            $image = $request->file('hinh_anh');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/promotions'), $imageName);
            $validated['hinh_anh'] = $imageName;
        }

        // Handle application scope
        $validated['ap_dung_tat_ca'] = $request->has('ap_dung_tat_ca') ? true : false;
        $validated['trang_thai'] = $request->has('trang_thai') ? true : false;
        if ($validated['ap_dung_tat_ca']) {
            $validated['san_pham_ap_dung'] = null;
            $validated['danh_muc_ap_dung'] = null;
        }

        $promotion->update($validated);

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
        $promotion->update(['trang_thai' => !$promotion->trang_thai]);

        $status = $promotion->trang_thai ? 'kích hoạt' : 'tắt';
        return response()->json([
            'success' => true,
            'message' => "Khuyến mãi đã được {$status} thành công!",
            'status' => $promotion->trang_thai
        ]);
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
        $promotion = KhuyenMai::byCode($code)->first();

        if (!$promotion) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi không tồn tại!'
            ]);
        }

        if (!$promotion->canUse()) {
            return response()->json([
                'valid' => false,
                'message' => 'Mã khuyến mãi không hợp lệ hoặc đã hết hạn!'
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
}
