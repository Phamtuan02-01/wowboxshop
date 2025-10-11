<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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

        if (Auth::user()->vaiTro->ten_vai_tro !== 'Admin') {
            return redirect()->route('trangchu')->with('error', 'Bạn không có quyền truy cập trang này');
        }

        return $next($request);
    }
}
