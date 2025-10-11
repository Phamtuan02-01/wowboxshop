<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaiKhoan;
use App\Models\VaiTro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = TaiKhoan::with('vaiTro')->withTrashed();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ho_ten', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('ten_dang_nhap', 'LIKE', "%{$search}%")
                  ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('ma_vai_tro', $request->role);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'ngay_tao');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(10)->appends($request->query());
        $vaiTros = VaiTro::all();

        // Statistics
        $stats = [
            'total' => TaiKhoan::withTrashed()->count(),
            'active' => TaiKhoan::count(),
            'inactive' => TaiKhoan::onlyTrashed()->count(),
        ];

        // Role statistics
        $roleStats = VaiTro::with('taiKhoans')->get();
        $stats['admin'] = 0;
        $stats['customer'] = 0;
        
        foreach ($roleStats as $role) {
            if (strtolower($role->ten_vai_tro) === 'admin') {
                $stats['admin'] = $role->taiKhoans->count();
            } elseif (in_array(strtolower($role->ten_vai_tro), ['khach hang', 'khách hàng', 'customer'])) {
                $stats['customer'] = $role->taiKhoans->count();
            }
        }
        
        // New users this month
        $stats['new_this_month'] = TaiKhoan::whereMonth('ngay_tao', now()->month)
            ->whereYear('ngay_tao', now()->year)
            ->count();

        return view('admin.users.index', compact('users', 'vaiTros', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $vaiTros = VaiTro::all();
        return view('admin.users.create', compact('vaiTros'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ten_dang_nhap' => 'required|string|max:255|unique:tai_khoan,ten_dang_nhap',
            'email' => 'required|string|email|max:255|unique:tai_khoan,email',
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20|unique:tai_khoan,so_dien_thoai',
            'mat_khau' => 'required|string|min:6|confirmed',
            'ma_vai_tro' => 'required|exists:vai_tro,ma_vai_tro',
        ], [
            'ten_dang_nhap.required' => 'Tên đăng nhập là bắt buộc.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'ho_ten.required' => 'Họ tên là bắt buộc.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại.',
            'mat_khau.required' => 'Mật khẩu là bắt buộc.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'ma_vai_tro.required' => 'Vai trò là bắt buộc.',
            'ma_vai_tro.exists' => 'Vai trò không tồn tại.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            TaiKhoan::create([
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'email' => $request->email,
                'ho_ten' => $request->ho_ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'mat_khau_hash' => Hash::make($request->mat_khau),
                'ma_vai_tro' => $request->ma_vai_tro,
                'ngay_tao' => now(),
                'ngay_cap_nhat' => now(),
            ]);

            return redirect()->route('admin.users.index')
                ->with('success', 'Tạo người dùng mới thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi tạo người dùng: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        $user = TaiKhoan::with(['vaiTro', 'donHangs.chiTietDonHangs.sanPham'])
            ->withTrashed()
            ->findOrFail($id);

        // User statistics
        $completedOrders = $user->donHangs->where('trang_thai', 'Đã giao');
        $stats = [
            'total_orders' => $user->donHangs->count(),
            'completed_orders' => $completedOrders->count(),
            'pending_orders' => $user->donHangs->where('trang_thai', 'Chờ xử lý')->count(),
            'total_spent' => $completedOrders->sum('tong_tien') ?? 0,
        ];

        // Recent orders
        $recentOrders = $user->donHangs()
            ->with('chiTietDonHangs.sanPham')
            ->orderBy('ngay_dat_hang', 'desc')
            ->limit(5)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentOrders'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit($id)
    {
        $user = TaiKhoan::with('vaiTro')->withTrashed()->findOrFail($id);
        $vaiTros = VaiTro::all();
        
        return view('admin.users.edit', compact('user', 'vaiTros'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = TaiKhoan::withTrashed()->findOrFail($id);

        $rules = [
            'ten_dang_nhap' => 'required|string|max:255|unique:tai_khoan,ten_dang_nhap,' . $id . ',ma_tai_khoan',
            'email' => 'required|string|email|max:255|unique:tai_khoan,email,' . $id . ',ma_tai_khoan',
            'ho_ten' => 'required|string|max:255',
            'so_dien_thoai' => 'required|string|max:20|unique:tai_khoan,so_dien_thoai,' . $id . ',ma_tai_khoan',
            'ma_vai_tro' => 'required|exists:vai_tro,ma_vai_tro',
        ];

        // Only validate password if it's provided
        if ($request->filled('mat_khau')) {
            $rules['mat_khau'] = 'string|min:6|confirmed';
        }

        $validator = Validator::make($request->all(), $rules, [
            'ten_dang_nhap.required' => 'Tên đăng nhập là bắt buộc.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại.',
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'email.unique' => 'Email đã tồn tại.',
            'ho_ten.required' => 'Họ tên là bắt buộc.',
            'so_dien_thoai.required' => 'Số điện thoại là bắt buộc.',
            'so_dien_thoai.unique' => 'Số điện thoại đã tồn tại.',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'ma_vai_tro.required' => 'Vai trò là bắt buộc.',
            'ma_vai_tro.exists' => 'Vai trò không tồn tại.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $updateData = [
                'ten_dang_nhap' => $request->ten_dang_nhap,
                'email' => $request->email,
                'ho_ten' => $request->ho_ten,
                'so_dien_thoai' => $request->so_dien_thoai,
                'ma_vai_tro' => $request->ma_vai_tro,
                'ngay_cap_nhat' => now(),
            ];

            // Only update password if provided
            if ($request->filled('mat_khau')) {
                $updateData['mat_khau_hash'] = Hash::make($request->mat_khau);
            }

            $user->update($updateData);

            return redirect()->route('admin.users.show', $user->ma_tai_khoan)
                ->with('success', 'Cập nhật thông tin người dùng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified user from storage (soft delete).
     */
    public function destroy($id)
    {
        try {
            $user = TaiKhoan::findOrFail($id);
            $user->delete(); // Soft delete

            return redirect()->route('admin.users.index')
                ->with('success', 'Vô hiệu hóa người dùng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Restore a soft deleted user.
     */
    public function restore($id)
    {
        try {
            $user = TaiKhoan::withTrashed()->findOrFail($id);
            $user->restore();

            return redirect()->route('admin.users.index')
                ->with('success', 'Khôi phục người dùng thành công!');
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status (activate/deactivate).
     */
    public function toggleStatus($id)
    {
        try {
            $user = TaiKhoan::withTrashed()->findOrFail($id);
            
            if ($user->trashed()) {
                $user->restore();
                $message = 'Kích hoạt người dùng thành công!';
            } else {
                $user->delete();
                $message = 'Vô hiệu hóa người dùng thành công!';
            }

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'status' => $user->trashed() ? 'inactive' : 'active'
                ]);
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
                ]);
            }
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Export users to Excel.
     */
    public function export(Request $request)
    {
        try {
            $query = TaiKhoan::with('vaiTro')->withTrashed();

            // Apply same filters as index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('ho_ten', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('ten_dang_nhap', 'LIKE', "%{$search}%")
                      ->orWhere('so_dien_thoai', 'LIKE', "%{$search}%");
                });
            }

            if ($request->filled('role')) {
                $query->where('ma_vai_tro', $request->role);
            }

            if ($request->filled('status')) {
                if ($request->status == 'active') {
                    $query->whereNull('deleted_at');
                } elseif ($request->status == 'inactive') {
                    $query->whereNotNull('deleted_at');
                }
            }

            $users = $query->get();

            // Create CSV content
            $filename = 'danh-sach-nguoi-dung-' . date('Y-m-d-H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Add headers
                fputcsv($file, [
                    'Mã tài khoản',
                    'Tên đăng nhập', 
                    'Họ tên',
                    'Email',
                    'Số điện thoại',
                    'Vai trò',
                    'Trạng thái',
                    'Ngày tạo',
                    'Ngày cập nhật'
                ]);

                // Add data
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->ma_tai_khoan,
                        $user->ten_dang_nhap,
                        $user->ho_ten,
                        $user->email,
                        $user->so_dien_thoai,
                        $user->vaiTro->ten_vai_tro ?? 'N/A',
                        $user->trashed() ? 'Vô hiệu hóa' : 'Hoạt động',
                        $user->ngay_tao ? $user->ngay_tao->format('d/m/Y H:i:s') : 'N/A',
                        $user->ngay_cap_nhat ? $user->ngay_cap_nhat->format('d/m/Y H:i:s') : 'N/A'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xuất file: ' . $e->getMessage());
        }
    }
}