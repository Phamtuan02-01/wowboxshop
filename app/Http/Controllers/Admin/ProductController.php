<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = SanPham::with(['danhMuc']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_san_pham', 'LIKE', "%{$search}%")
                  ->orWhere('mo_ta', 'LIKE', "%{$search}%")
                  ->orWhere('thuong_hieu', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('ma_danh_muc', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('trang_thai', true);
            } elseif ($request->status === 'inactive') {
                $query->where('trang_thai', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'ngay_tao');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if ($sortBy === 'name') {
            $query->orderBy('ten_san_pham', $sortOrder);
        } elseif ($sortBy === 'price') {
            $query->orderBy('gia', $sortOrder);
        } elseif ($sortBy === 'stock') {
            $query->orderBy('so_luong_ton_kho', $sortOrder);
        } else {
            $query->orderBy('ngay_tao', $sortOrder);
        }

        $products = $query->paginate(20);
        $categories = DanhMuc::orderBy('ten_danh_muc')->get();

        // Statistics
        $stats = [
            'total_products' => SanPham::count(),
            'active_products' => SanPham::where('trang_thai', true)->count(),
            'inactive_products' => SanPham::where('trang_thai', false)->count(),
            'out_of_stock' => SanPham::where('so_luong_ton_kho', '<=', 0)->count(),
        ];

        return view('admin.products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = DanhMuc::orderBy('ten_danh_muc')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_san_pham' => 'required|string|max:100',
            'ma_danh_muc' => 'required|exists:danh_muc,ma_danh_muc',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'gia_khuyen_mai' => 'nullable|numeric|min:0|lt:gia',
            'so_luong_ton_kho' => 'required|integer|min:0',
            'thuong_hieu' => 'nullable|string|max:100',
            'xuat_xu' => 'nullable|string|max:100',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'boolean',
            'la_noi_bat' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $productData = [
                'ten_san_pham' => $request->ten_san_pham,
                'ma_danh_muc' => $request->ma_danh_muc,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'gia_khuyen_mai' => $request->gia_khuyen_mai,
                'so_luong_ton_kho' => $request->so_luong_ton_kho,
                'thuong_hieu' => $request->thuong_hieu,
                'xuat_xu' => $request->xuat_xu,
                'trang_thai' => $request->has('trang_thai'),
                'la_noi_bat' => $request->has('la_noi_bat'),
            ];

            // Handle image upload
            if ($request->hasFile('hinh_anh')) {
                $image = $request->file('hinh_anh');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $productData['hinh_anh'] = $imageName;
            }

            $product = SanPham::create($productData);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $product = SanPham::with(['danhMuc'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = SanPham::findOrFail($id);
        $categories = DanhMuc::orderBy('ten_danh_muc')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = SanPham::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'ten_san_pham' => 'required|string|max:100',
            'ma_danh_muc' => 'required|exists:danh_muc,ma_danh_muc',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'gia_khuyen_mai' => 'nullable|numeric|min:0|lt:gia',
            'so_luong_ton_kho' => 'required|integer|min:0',
            'thuong_hieu' => 'nullable|string|max:100',
            'xuat_xu' => 'nullable|string|max:100',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'trang_thai' => 'boolean',
            'la_noi_bat' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $productData = [
                'ten_san_pham' => $request->ten_san_pham,
                'ma_danh_muc' => $request->ma_danh_muc,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'gia_khuyen_mai' => $request->gia_khuyen_mai,
                'so_luong_ton_kho' => $request->so_luong_ton_kho,
                'thuong_hieu' => $request->thuong_hieu,
                'xuat_xu' => $request->xuat_xu,
                'trang_thai' => $request->has('trang_thai'),
                'la_noi_bat' => $request->has('la_noi_bat'),
            ];

            // Handle image upload
            if ($request->hasFile('hinh_anh')) {
                // Delete old image
                if ($product->hinh_anh && file_exists(public_path('images/products/' . $product->hinh_anh))) {
                    unlink(public_path('images/products/' . $product->hinh_anh));
                }
                
                $image = $request->file('hinh_anh');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images/products'), $imageName);
                $productData['hinh_anh'] = $imageName;
            }

            $product->update($productData);

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $product = SanPham::findOrFail($id);
        
        try {
            DB::beginTransaction();

            // Delete image
            if ($product->hinh_anh && file_exists(public_path('images/products/' . $product->hinh_anh))) {
                unlink(public_path('images/products/' . $product->hinh_anh));
            }

            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa sản phẩm: ' . $e->getMessage());
        }
    }

    public function toggleStatus($id)
    {
        $product = SanPham::findOrFail($id);
        
        try {
            $product->update(['trang_thai' => !$product->trang_thai]);
            
            $status = $product->trang_thai ? 'kích hoạt' : 'tạm ngưng';
            return redirect()->back()
                ->with('success', "Sản phẩm đã được {$status} thành công!");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi thay đổi trạng thái sản phẩm.');
        }
    }

    public function bulkAction(Request $request)
    {
        $action = $request->input('action');
        $productIds = explode(',', $request->input('product_ids', ''));

        if (empty($productIds)) {
            return redirect()->back()->with('error', 'Vui lòng chọn ít nhất một sản phẩm.');
        }

        try {
            DB::beginTransaction();

            switch ($action) {
                case 'activate':
                    SanPham::whereIn('ma_san_pham', $productIds)->update(['trang_thai' => true]);
                    $message = 'Các sản phẩm đã được kích hoạt thành công!';
                    break;
                
                case 'deactivate':
                    SanPham::whereIn('ma_san_pham', $productIds)->update(['trang_thai' => false]);
                    $message = 'Các sản phẩm đã được tạm ngưng thành công!';
                    break;
                
                case 'delete':
                    SanPham::whereIn('ma_san_pham', $productIds)->delete();
                    $message = 'Các sản phẩm đã được xóa thành công!';
                    break;
                
                default:
                    return redirect()->back()->with('error', 'Hành động không hợp lệ.');
            }

            DB::commit();
            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
