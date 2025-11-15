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
        $query = SanPham::with(['danhMuc', 'bienThes']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ten_san_pham', 'LIKE', "%{$search}%")
                  ->orWhere('mo_ta', 'LIKE', "%{$search}%")
                  ->orWhere('thuong_hieu', 'LIKE', "%{$search}%")
                  ->orWhere('xuat_xu', 'LIKE', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('ma_danh_muc', $request->category);
        }

        // Product type filter
        if ($request->filled('type')) {
            $query->where('loai_san_pham', $request->type);
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
        } elseif ($sortBy === 'rating') {
            $query->orderBy('diem_danh_gia_trung_binh', $sortOrder);
        } elseif ($sortBy === 'views') {
            $query->orderBy('luot_xem', $sortOrder);
        } else {
            $query->orderBy('ngay_tao', $sortOrder);
        }

        $products = $query->paginate(20);
        $categories = DanhMuc::orderBy('ten_danh_muc')->get();

        // Statistics
        $stats = [
            'total_products' => SanPham::count(),
            'total_regular_products' => SanPham::where('loai_san_pham', 'product')->count(),
            'total_ingredients' => SanPham::where('loai_san_pham', 'ingredient')->count(),
            'active_products' => SanPham::where('trang_thai', true)->count(),
            'inactive_products' => SanPham::where('trang_thai', false)->count(),
            'featured_products' => SanPham::where('la_noi_bat', true)->count(),
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
            'loai_san_pham' => 'required|in:product,ingredient',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thuong_hieu' => 'nullable|string|max:100',
            'xuat_xu' => 'nullable|string|max:100',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'hinh_anh_phu' => 'nullable|array',
            'hinh_anh_phu.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
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
                'loai_san_pham' => $request->loai_san_pham,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'thuong_hieu' => $request->thuong_hieu,
                'xuat_xu' => $request->xuat_xu,
                'trang_thai' => $request->has('trang_thai'),
                'la_noi_bat' => $request->has('la_noi_bat'),
                'luot_xem' => 0,
                'diem_danh_gia_trung_binh' => 0,
                'so_luot_danh_gia' => 0,
            ];

            // Handle main image upload
            if ($request->hasFile('hinh_anh')) {
                $image = $request->file('hinh_anh');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('images/products');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $productData['hinh_anh'] = $imageName;
            }

            // Handle additional images
            $additionalImages = [];
            if ($request->hasFile('hinh_anh_phu')) {
                foreach ($request->file('hinh_anh_phu') as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/products'), $fileName);
                    $additionalImages[] = $fileName;
                }
                $productData['hinh_anh_phu'] = json_encode($additionalImages);
            }

            $product = SanPham::create($productData);

            DB::commit();

            $productType = $request->loai_san_pham === 'ingredient' ? 'nguyên liệu' : 'sản phẩm';
            return redirect()->route('admin.products.index')
                ->with('success', 'Đã tạo ' . $productType . ' thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $product = SanPham::with(['danhMuc', 'bienThes.chiTietDonHangs'])->findOrFail($id);
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
            'loai_san_pham' => 'required|in:product,ingredient',
            'mo_ta' => 'nullable|string',
            'gia' => 'required|numeric|min:0',
            'thuong_hieu' => 'nullable|string|max:100',
            'xuat_xu' => 'nullable|string|max:100',
            'hinh_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'hinh_anh_phu' => 'nullable|array',
            'hinh_anh_phu.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
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
                'loai_san_pham' => $request->loai_san_pham,
                'mo_ta' => $request->mo_ta,
                'gia' => $request->gia,
                'thuong_hieu' => $request->thuong_hieu,
                'xuat_xu' => $request->xuat_xu,
                'trang_thai' => $request->has('trang_thai'),
                'la_noi_bat' => $request->has('la_noi_bat'),
            ];

            // Handle main image upload
            if ($request->hasFile('hinh_anh')) {
                // Delete old image
                if ($product->hinh_anh && file_exists(public_path('images/products/' . $product->hinh_anh))) {
                    unlink(public_path('images/products/' . $product->hinh_anh));
                }
                
                $image = $request->file('hinh_anh');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                
                // Create directory if it doesn't exist
                $uploadPath = public_path('images/products');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                $image->move($uploadPath, $imageName);
                $productData['hinh_anh'] = $imageName;
            }

            // Handle additional images
            if ($request->hasFile('hinh_anh_phu')) {
                // Delete old additional images
                if ($product->hinh_anh_phu) {
                    $oldImages = json_decode($product->hinh_anh_phu, true);
                    if (is_array($oldImages)) {
                        foreach ($oldImages as $oldImage) {
                            if (file_exists(public_path('images/products/' . $oldImage))) {
                                unlink(public_path('images/products/' . $oldImage));
                            }
                        }
                    }
                }
                
                $additionalImages = [];
                foreach ($request->file('hinh_anh_phu') as $file) {
                    $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('images/products'), $fileName);
                    $additionalImages[] = $fileName;
                }
                $productData['hinh_anh_phu'] = json_encode($additionalImages);
            }

            $product->update($productData);

            DB::commit();

            $productType = $request->loai_san_pham === 'ingredient' ? 'nguyên liệu' : 'sản phẩm';
            return redirect()->route('admin.products.index')
                ->with('success', 'Đã cập nhật ' . $productType . ' thành công!');

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

    /**
     * Get product information for variant creation
     */
    public function getProductInfo($id)
    {
        $product = SanPham::with('danhMuc')->findOrFail($id);
        $variantCount = $product->bienThes()->count();

        return response()->json([
            'success' => true,
            'product' => [
                'ma_san_pham' => $product->ma_san_pham,
                'ten_san_pham' => $product->ten_san_pham,
                'gia' => $product->gia,
                'danh_muc' => $product->danhMuc ? [
                    'ma_danh_muc' => $product->danhMuc->ma_danh_muc,
                    'ten_danh_muc' => $product->danhMuc->ten_danh_muc,
                ] : null,
            ],
            'variant_count' => $variantCount,
        ]);
    }
}
