<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request for super admin actions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('dangnhap')->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        $user = Auth::user();
        
        // Check if user has super admin privileges (you can add more conditions)
        if (!$user->vaiTro || strtolower($user->vaiTro->ten_vai_tro) !== 'admin') {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không có quyền thực hiện hành động này');
        }

        // Additional check: prevent admin from modifying their own account
        if ($request->route('id') && $request->route('id') == $user->ma_tai_khoan) {
            return redirect()->route('admin.dashboard')->with('error', 'Bạn không thể chỉnh sửa tài khoản của chính mình');
        }

        return $next($request);
    }
}