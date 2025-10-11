<?php

namespace App\Http\Middleware\Admin;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
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
        
        // Check if user has admin role
        if (!$user->vaiTro || strtolower($user->vaiTro->ten_vai_tro) !== 'admin') {
            return redirect()->route('trangchu')->with('error', 'Bạn không có quyền truy cập trang này');
        }

        return $next($request);
    }
}