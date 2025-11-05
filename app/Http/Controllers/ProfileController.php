<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TaiKhoan;
use App\Models\DonHang;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Lấy thống kê đơn hàng
        $tongDonHang = DonHang::where('ma_tai_khoan', $user->ma_tai_khoan)->count();
        $donHangChoXacNhan = DonHang::where('ma_tai_khoan', $user->ma_tai_khoan)
                                    ->where('trang_thai', 'cho_xac_nhan')
                                    ->count();
        $donHangDangGiao = DonHang::where('ma_tai_khoan', $user->ma_tai_khoan)
                                  ->where('trang_thai', 'dang_giao')
                                  ->count();
        $donHangHoanThanh = DonHang::where('ma_tai_khoan', $user->ma_tai_khoan)
                                   ->where('trang_thai', 'da_giao')
                                   ->count();
        
        return view('profile.index', compact(
            'user',
            'tongDonHang',
            'donHangChoXacNhan',
            'donHangDangGiao',
            'donHangHoanThanh'
        ));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'ten_dang_nhap' => 'required|string|max:50|unique:tai_khoan,ten_dang_nhap,' . $user->ma_tai_khoan . ',ma_tai_khoan',
            'email' => 'required|email|max:100|unique:tai_khoan,email,' . $user->ma_tai_khoan . ',ma_tai_khoan',
            'ho_ten' => 'required|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi' => 'nullable|string|max:255',
        ], [
            'ten_dang_nhap.required' => 'Tên đăng nhập không được để trống',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'ho_ten.required' => 'Họ tên không được để trống',
        ]);

        $user->update($validated);

        return redirect()->route('profile.index')
                        ->with('success', 'Cập nhật thông tin thành công!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->mat_khau)) {
            return back()->with('error', 'Mật khẩu hiện tại không đúng!');
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'mat_khau' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile.index')
                        ->with('success', 'Đổi mật khẩu thành công!');
    }
}
