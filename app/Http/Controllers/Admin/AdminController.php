<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SanPham;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\DanhMuc;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Hiển thị dashboard admin
     */
    public function dashboard()
    {
        // Thống kê tổng quan
        $totalUsers = User::count();
        $totalProducts = SanPham::count();
        $totalOrders = DonHang::count();
        
        // Tính tổng doanh thu (có thể là 0 nếu chưa có dữ liệu)
        $totalRevenue = 0;
        $donHangs = DonHang::with('chiTietDonHangs')->get();
        foreach ($donHangs as $donHang) {
            foreach ($donHang->chiTietDonHangs as $chiTiet) {
                $totalRevenue += $chiTiet->so_luong * $chiTiet->gia_tai_thoi_diem_mua;
            }
        }
        
        // Thống kê theo tháng (12 tháng gần nhất) - đơn giản hóa
        $monthlyRevenue = [];
        $monthlyOrders = [];
        $monthlyNewUsers = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            $monthlyRevenue[] = [
                'month' => $date->format('m/Y'),
                'revenue' => 0 // Sẽ tính sau khi có dữ liệu
            ];
            
            $monthlyOrders[] = [
                'month' => $date->format('m/Y'),
                'orders' => DonHang::whereBetween('ngay_dat_hang', [$startOfMonth, $endOfMonth])->count()
            ];
            
            $monthlyNewUsers[] = [
                'month' => $date->format('m/Y'),
                'users' => User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count()
            ];
        }
        
        // Top sản phẩm bán chạy - đơn giản hóa
        $topProducts = ChiTietDonHang::select('ma_san_pham', DB::raw('SUM(so_luong) as total_sold'))
            ->groupBy('ma_san_pham')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->with('sanPham')
            ->get();
        
        // Đơn hàng gần đây
        $recentOrders = DonHang::with(['taiKhoan', 'chiTietDonHangs.sanPham'])
            ->orderBy('ngay_dat_hang', 'desc')
            ->limit(10)
            ->get();
        
        // Thống kê trạng thái đơn hàng
        $orderStatus = DonHang::select('trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('trang_thai')
            ->get();
        
        // Thống kê theo ngày (7 ngày gần nhất) - đơn giản hóa
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString();
            
            $dailyStats[] = [
                'date' => $date->format('d/m'),
                'orders' => DonHang::whereDate('ngay_dat_hang', $dateString)->count(),
                'revenue' => 0, // Sẽ tính sau
                'users' => User::whereDate('created_at', $dateString)->count()
            ];
        }
        
        // Thống kê so với tháng trước - đơn giản hóa
        $currentMonth = Carbon::now();
        $lastMonth = Carbon::now()->subMonth();
        
        $currentMonthRevenue = 0;
        $lastMonthRevenue = 0;
        $revenueChange = 0;
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts', 
            'totalOrders',
            'totalRevenue',
            'monthlyRevenue',
            'monthlyOrders',
            'monthlyNewUsers',
            'topProducts',
            'recentOrders',
            'orderStatus',
            'dailyStats',
            'revenueChange'
        ));
    }
}