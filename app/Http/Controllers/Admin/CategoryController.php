<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DanhMuc;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index(Request $request)
    {
        $query = DanhMuc::with(['danhMucCha', 'danhMucCons', 'sanPhams']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('ten_danh_muc', 'LIKE', "%{$search}%");
        }
        
        // Filter by parent category
        if ($request->filled('parent_id')) {
            if ($request->parent_id == 'root') {
                $query->whereNull('ma_danh_muc_cha');
            } else {
                $query->where('ma_danh_muc_cha', $request->parent_id);
            }
        }
        
        $categories = $query->orderBy('ten_danh_muc')->paginate(15);
        $categories->withPath(request()->url());
        
        // Get all parent categories for filter dropdown
        $parentCategories = DanhMuc::whereNull('ma_danh_muc_cha')
            ->orderBy('ten_danh_muc')
            ->get();
        
        // Statistics
        $stats = [
            'total_categories' => DanhMuc::count(),
            'root_categories' => DanhMuc::whereNull('ma_danh_muc_cha')->count(),
            'child_categories' => DanhMuc::whereNotNull('ma_danh_muc_cha')->count(),
            'categories_with_products' => DanhMuc::has('sanPhams')->count(),
        ];
        
        return view('admin.categories.index', compact('categories', 'parentCategories', 'stats'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = DanhMuc::whereNull('ma_danh_muc_cha')
            ->orderBy('ten_danh_muc')
            ->get();
        
        return view('admin.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_danh_muc' => 'required|string|max:50|unique:danh_muc,ten_danh_muc',
            'ma_danh_muc_cha' => 'nullable|exists:danh_muc,ma_danh_muc',
        ], [
            'ten_danh_muc.required' => 'Tên danh mục là bắt buộc.',
            'ten_danh_muc.max' => 'Tên danh mục không được vượt quá 50 ký tự.',
            'ten_danh_muc.unique' => 'Tên danh mục đã tồn tại.',
            'ma_danh_muc_cha.exists' => 'Danh mục cha không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DanhMuc::create([
                'ten_danh_muc' => $request->ten_danh_muc,
                'ma_danh_muc_cha' => $request->ma_danh_muc_cha,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được tạo thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo danh mục.')
                ->withInput();
        }
    }

    /**
     * Display the specified category.
     */
    public function show(DanhMuc $category)
    {
        $category->load(['danhMucCha', 'danhMucCons', 'sanPhams']);
        
        // Get products in this category with pagination
        $products = $category->sanPhams()->with(['danhMuc'])->paginate(10);
        
        // Statistics for this category
        $stats = [
            'total_products' => $category->sanPhams()->count(),
            'child_categories' => $category->danhMucCons()->count(),
            'total_products_including_children' => $this->getTotalProductsIncludingChildren($category),
        ];
        
        return view('admin.categories.show', compact('category', 'products', 'stats'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(DanhMuc $category)
    {
        $parentCategories = DanhMuc::whereNull('ma_danh_muc_cha')
            ->where('ma_danh_muc', '!=', $category->ma_danh_muc)
            ->orderBy('ten_danh_muc')
            ->get();
        
        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, DanhMuc $category)
    {
        $validator = Validator::make($request->all(), [
            'ten_danh_muc' => 'required|string|max:50|unique:danh_muc,ten_danh_muc,' . $category->ma_danh_muc . ',ma_danh_muc',
            'ma_danh_muc_cha' => 'nullable|exists:danh_muc,ma_danh_muc',
        ], [
            'ten_danh_muc.required' => 'Tên danh mục là bắt buộc.',
            'ten_danh_muc.max' => 'Tên danh mục không được vượt quá 50 ký tự.',
            'ten_danh_muc.unique' => 'Tên danh mục đã tồn tại.',
            'ma_danh_muc_cha.exists' => 'Danh mục cha không hợp lệ.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prevent setting parent to itself or its children
        if ($request->ma_danh_muc_cha) {
            if ($request->ma_danh_muc_cha == $category->ma_danh_muc) {
                return redirect()->back()
                    ->with('error', 'Danh mục không thể là danh mục cha của chính nó.')
                    ->withInput();
            }
            
            // Check if the selected parent is a child of current category
            if ($this->isChildOfCategory($category->ma_danh_muc, $request->ma_danh_muc_cha)) {
                return redirect()->back()
                    ->with('error', 'Không thể chọn danh mục con làm danh mục cha.')
                    ->withInput();
            }
        }

        try {
            $category->update([
                'ten_danh_muc' => $request->ten_danh_muc,
                'ma_danh_muc_cha' => $request->ma_danh_muc_cha,
            ]);

            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được cập nhật thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật danh mục.')
                ->withInput();
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(DanhMuc $category)
    {
        try {
            // Check if category has products
            if ($category->sanPhams()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa danh mục vì còn có sản phẩm.');
            }
            
            // Check if category has child categories
            if ($category->danhMucCons()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Không thể xóa danh mục vì còn có danh mục con.');
            }

            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'Danh mục đã được xóa thành công!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa danh mục.');
        }
    }

    /**
     * Get tree structure of categories
     */
    public function getTree()
    {
        $categories = DanhMuc::with(['danhMucCons' => function($query) {
            $query->orderBy('ten_danh_muc');
        }])
        ->whereNull('ma_danh_muc_cha')
        ->orderBy('ten_danh_muc')
        ->get();

        return response()->json($categories);
    }

    /**
     * Check if a category is a child of another category
     */
    private function isChildOfCategory($parentId, $childId)
    {
        $category = DanhMuc::find($childId);
        
        while ($category && $category->ma_danh_muc_cha) {
            if ($category->ma_danh_muc_cha == $parentId) {
                return true;
            }
            $category = $category->danhMucCha;
        }
        
        return false;
    }

    /**
     * Get total products including children categories
     */
    private function getTotalProductsIncludingChildren(DanhMuc $category)
    {
        $total = $category->sanPhams()->count();
        
        foreach ($category->danhMucCons as $child) {
            $total += $this->getTotalProductsIncludingChildren($child);
        }
        
        return $total;
    }
}