<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BienTheSanPham;
use App\Models\SanPham;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = BienTheSanPham::with(['sanPham', 'sanPham.danhMuc']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('kich_thuoc', 'like', "%{$search}%")
                  ->orWhereHas('sanPham', function($productQuery) use ($search) {
                      $productQuery->where('ten_san_pham', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('ma_san_pham', $request->get('product_id'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->get('status'));
        }
        
        // Filter by stock status
        if ($request->filled('stock_status')) {
            switch ($request->get('stock_status')) {
                case 'in_stock':
                    $query->where('so_luong_ton', '>', 10);
                    break;
                case 'low_stock':
                    $query->whereBetween('so_luong_ton', [1, 10]);
                    break;
                case 'out_of_stock':
                    $query->where('so_luong_ton', '<=', 0);
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort_by', 'ngay_tao');
        $sortDirection = $request->get('sort_direction', 'desc');
        
        if ($sortBy === 'product_name') {
            $query->join('san_pham', 'bien_the_san_pham.ma_san_pham', '=', 'san_pham.ma_san_pham')
                  ->orderBy('san_pham.ten_san_pham', $sortDirection)
                  ->select('bien_the_san_pham.*');
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }
        
        $variants = $query->paginate(15)->withQueryString();
        
        // Get products for filter dropdown
        $products = SanPham::orderBy('ten_san_pham')->get();
        
        return view('admin.product-variants.index', compact('variants', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $products = SanPham::with('danhMuc')->orderBy('ten_san_pham')->get();
        
        $selectedProduct = null;
        if ($request->filled('product_id')) {
            $selectedProduct = SanPham::find($request->get('product_id'));
        }
        
        return view('admin.product-variants.create', compact('products', 'selectedProduct'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'kich_thuoc' => 'required|string|max:20',
            'gia' => 'required|numeric|min:0',
            'calo' => 'required|integer|min:0',
            'so_luong_ton' => 'required|integer|min:0',
            'trang_thai' => 'boolean',
        ], [
            'ma_san_pham.required' => 'Vui lòng chọn sản phẩm',
            'ma_san_pham.exists' => 'Sản phẩm không tồn tại',
            'kich_thuoc.required' => 'Vui lòng nhập kích thước',
            'kich_thuoc.max' => 'Kích thước không được quá 20 ký tự',
            'gia.required' => 'Vui lòng nhập giá',
            'gia.numeric' => 'Giá phải là số',
            'gia.min' => 'Giá phải lớn hơn hoặc bằng 0',
            'calo.required' => 'Vui lòng nhập số calo',
            'calo.integer' => 'Calo phải là số nguyên',
            'calo.min' => 'Calo phải lớn hơn hoặc bằng 0',
            'so_luong_ton.required' => 'Vui lòng nhập số lượng tồn',
            'so_luong_ton.integer' => 'Số lượng tồn phải là số nguyên',
            'so_luong_ton.min' => 'Số lượng tồn phải lớn hơn hoặc bằng 0',
        ]);
        
        // Check for duplicate variant
        $existingVariant = BienTheSanPham::where('ma_san_pham', $request->ma_san_pham)
                                       ->where('kich_thuoc', $request->kich_thuoc)
                                       ->first();
        
        if ($existingVariant) {
            return back()->withErrors(['kich_thuoc' => 'Biến thể với kích thước này đã tồn tại cho sản phẩm đã chọn.'])
                        ->withInput();
        }
        
        $data = $request->all();
        $data['trang_thai'] = $request->has('trang_thai');
        
        BienTheSanPham::create($data);
        
        return redirect()->route('admin.product-variants.index')
                        ->with('success', 'Biến thể sản phẩm đã được tạo thành công!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $variant = BienTheSanPham::with([
            'sanPham.danhMuc', 
            'chiTietDonHangs.donHang', 
            'chiTietGioHangs'
        ])->findOrFail($id);
        
        return view('admin.product-variants.show', compact('variant'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $variant = BienTheSanPham::with('sanPham')->findOrFail($id);
        $products = SanPham::with('danhMuc')->orderBy('ten_san_pham')->get();
        
        return view('admin.product-variants.edit', compact('variant', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $variant = BienTheSanPham::findOrFail($id);
        
        $request->validate([
            'ma_san_pham' => 'required|exists:san_pham,ma_san_pham',
            'kich_thuoc' => 'required|string|max:20',
            'gia' => 'required|numeric|min:0',
            'calo' => 'required|integer|min:0',
            'so_luong_ton' => 'required|integer|min:0',
            'trang_thai' => 'boolean',
        ], [
            'ma_san_pham.required' => 'Vui lòng chọn sản phẩm',
            'ma_san_pham.exists' => 'Sản phẩm không tồn tại',
            'kich_thuoc.required' => 'Vui lòng nhập kích thước',
            'kich_thuoc.max' => 'Kích thước không được quá 20 ký tự',
            'gia.required' => 'Vui lòng nhập giá',
            'gia.numeric' => 'Giá phải là số',
            'gia.min' => 'Giá phải lớn hơn hoặc bằng 0',
            'calo.required' => 'Vui lòng nhập số calo',
            'calo.integer' => 'Calo phải là số nguyên',
            'calo.min' => 'Calo phải lớn hơn hoặc bằng 0',
            'so_luong_ton.required' => 'Vui lòng nhập số lượng tồn',
            'so_luong_ton.integer' => 'Số lượng tồn phải là số nguyên',
            'so_luong_ton.min' => 'Số lượng tồn phải lớn hơn hoặc bằng 0',
        ]);
        
        // Check for duplicate variant (excluding current variant)
        $existingVariant = BienTheSanPham::where('ma_san_pham', $request->ma_san_pham)
                                       ->where('kich_thuoc', $request->kich_thuoc)
                                       ->where('ma_bien_the', '!=', $id)
                                       ->first();
        
        if ($existingVariant) {
            return back()->withErrors(['kich_thuoc' => 'Biến thể với kích thước này đã tồn tại cho sản phẩm đã chọn.'])
                        ->withInput();
        }
        
        $data = $request->all();
        $data['trang_thai'] = $request->has('trang_thai');
        
        $variant->update($data);
        
        return redirect()->route('admin.product-variants.index')
                        ->with('success', 'Biến thể sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variant = BienTheSanPham::findOrFail($id);
        
        // Check if variant is used in orders or carts
        if ($variant->chiTietDonHangs()->exists() || $variant->chiTietGioHangs()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xóa biến thể này vì đã có đơn hàng hoặc giỏ hàng sử dụng.'
            ]);
        }
        
        $variant->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Biến thể sản phẩm đã được xóa thành công!'
        ]);
    }
    
    /**
     * Toggle status of variant
     */
    public function toggleStatus($id)
    {
        $variant = BienTheSanPham::findOrFail($id);
        $variant->trang_thai = !$variant->trang_thai;
        $variant->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Trạng thái biến thể đã được cập nhật!',
            'status' => $variant->trang_thai
        ]);
    }
    
    /**
     * Bulk actions
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'selected_variants' => 'required|array',
            'selected_variants.*' => 'exists:bien_the_san_pham,ma_bien_the'
        ]);
        
        $variants = BienTheSanPham::whereIn('ma_bien_the', $request->selected_variants);
        $count = $variants->count();
        
        switch ($request->action) {
            case 'activate':
                $variants->update(['trang_thai' => true]);
                $message = "Đã kích hoạt {$count} biến thể sản phẩm.";
                break;
                
            case 'deactivate':
                $variants->update(['trang_thai' => false]);
                $message = "Đã vô hiệu hóa {$count} biến thể sản phẩm.";
                break;
                
            case 'delete':
                // Check if any variants are used in orders or carts
                $usedVariants = $variants->whereHas('chiTietDonHangs')
                                       ->orWhereHas('chiTietGioHangs')
                                       ->count();
                
                if ($usedVariants > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => "Không thể xóa {$usedVariants} biến thể vì đã có đơn hàng hoặc giỏ hàng sử dụng."
                    ]);
                }
                
                $variants->delete();
                $message = "Đã xóa {$count} biến thể sản phẩm.";
                break;
        }
        
        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }
}
