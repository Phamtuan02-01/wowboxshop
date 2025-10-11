<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\TaiKhoan;
use App\Models\VaiTro;
use App\Helpers\FlashMessage;

class DangKyController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function hienThiFormDangKy()
    {
        return view('auth.dangky');
    }

    /**
     * Xử lý đăng ký
     */
    public function xuLyDangKy(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string|max:50|unique:tai_khoan,ten_dang_nhap',
            'ho_ten' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:tai_khoan,email',
            'so_dien_thoai' => 'required|string|max:20|unique:tai_khoan,so_dien_thoai',
            'mat_khau' => 'required|string|min:6|confirmed',
        ], [
            'ten_dang_nhap.required' => 'Tên đăng nhập không được để trống',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã tồn tại',
            'ten_dang_nhap.max' => 'Tên đăng nhập không được quá 50 ký tự',
            'ho_ten.required' => 'Họ tên không được để trống',
            'ho_ten.max' => 'Họ tên không được quá 100 ký tự',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã được sử dụng',
            'email.max' => 'Email không được quá 100 ký tự',
            'so_dien_thoai.required' => 'Số điện thoại không được để trống',
            'so_dien_thoai.max' => 'Số điện thoại không được quá 20 ký tự',
            'so_dien_thoai.unique' => 'Số điện thoại đã được sử dụng',
            'mat_khau.required' => 'Mật khẩu không được để trống',
            'mat_khau.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Lấy vai trò mặc định (khách hàng)
        $vaiTroKhachHang = VaiTro::where('ten_vai_tro', 'Khach hang')->first();
        
        if (!$vaiTroKhachHang) {
            // Nếu không tìm thấy vai trò "Khach hang", tạo mới
            $vaiTroKhachHang = VaiTro::create([
                'ten_vai_tro' => 'Khach hang'
            ]);
        }

        // Tạo tài khoản mới với vai trò khách hàng mặc định
        $taiKhoan = TaiKhoan::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'ho_ten' => $request->ho_ten,
            'email' => $request->email,
            'so_dien_thoai' => $request->so_dien_thoai,
            'mat_khau_hash' => Hash::make($request->mat_khau),
            'ma_vai_tro' => $vaiTroKhachHang->ma_vai_tro,
        ]);

        FlashMessage::success('Đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ.');
        return redirect()->route('dangnhap');
    }
}