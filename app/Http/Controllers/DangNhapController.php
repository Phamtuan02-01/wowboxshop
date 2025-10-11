<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\TaiKhoan;
use App\Models\VaiTro;
use App\Helpers\FlashMessage;

class DangNhapController extends Controller
{
    /**
     * Hiển thị form đăng nhập
     */
    public function hienThiFormDangNhap()
    {
        return view('auth.dangnhap');
    }

    /**
     * Xử lý đăng nhập
     */
    public function xuLyDangNhap(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string',
            'mat_khau' => 'required|string|min:6',
        ], [
            'ten_dang_nhap.required' => 'Tên đăng nhập không được để trống',
            'mat_khau.required' => 'Mật khẩu không được để trống',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        $tenDangNhap = $request->ten_dang_nhap;
        $matKhau = $request->mat_khau;

        // Tìm tài khoản theo tên đăng nhập hoặc email
        $taiKhoan = TaiKhoan::where('ten_dang_nhap', $tenDangNhap)
                           ->orWhere('email', $tenDangNhap)
                           ->first();

        if ($taiKhoan && Hash::check($matKhau, $taiKhoan->mat_khau_hash)) {
            Auth::login($taiKhoan);
            
            // Chuyển hướng theo vai trò
            if ($taiKhoan->vaiTro->ten_vai_tro === 'Admin') {
                FlashMessage::success('Chào mừng Admin ' . $taiKhoan->ten_dang_nhap . '!');
                return redirect()->route('admin.dashboard');
            } else {
                FlashMessage::success('Đăng nhập thành công! Chào mừng ' . $taiKhoan->ten_dang_nhap);
                return redirect()->route('trangchu');
            }
        }

        return back()->withErrors([
            'ten_dang_nhap' => 'Tên đăng nhập hoặc mật khẩu không chính xác.',
        ])->withInput();
    }

    /**
     * Đăng xuất
     */
    public function dangXuat()
    {
        Auth::logout();
        FlashMessage::info('Đăng xuất thành công! Hẹn gặp lại bạn.');
        return redirect()->route('dangnhap');
    }
}