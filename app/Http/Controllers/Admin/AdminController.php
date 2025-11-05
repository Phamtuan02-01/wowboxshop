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
        
        // Tính tổng doanh thu (CHỈ tính đơn đã giao, KHÔNG tính đơn hủy)
        $totalRevenue = 0;
        $donHangs = DonHang::with('chiTietDonHangs')
            ->where('trang_thai', 'da_giao') // Chỉ tính đơn đã giao
            ->get();
        foreach ($donHangs as $donHang) {
            foreach ($donHang->chiTietDonHangs as $chiTiet) {
                $totalRevenue += $chiTiet->so_luong * $chiTiet->gia_tai_thoi_diem_mua;
            }
        }
        
        // Thống kê theo tháng (12 tháng gần nhất) - chỉ tính đơn đã giao
        $monthlyRevenue = [];
        $monthlyOrders = [];
        $monthlyNewUsers = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();
            
            // Tính revenue từ đơn đã giao trong tháng
            $monthRevenue = DonHang::where('trang_thai', 'da_giao')
                ->whereBetween('ngay_dat_hang', [$startOfMonth, $endOfMonth])
                ->sum('tong_tien');
            
            $monthlyRevenue[] = [
                'month' => $date->format('m/Y'),
                'revenue' => $monthRevenue
            ];
            
            $monthlyOrders[] = [
                'month' => $date->format('m/Y'),
                'orders' => DonHang::whereBetween('ngay_dat_hang', [$startOfMonth, $endOfMonth])->count()
            ];
            
            $monthlyNewUsers[] = [
                'month' => $date->format('m/Y'),
                'users' => User::whereBetween('ngay_tao', [$startOfMonth, $endOfMonth])->count()
            ];
        }
        
        // Top sản phẩm bán chạy - chỉ tính từ đơn đã giao
        $topProducts = ChiTietDonHang::select('ma_san_pham', DB::raw('SUM(so_luong) as total_sold'))
            ->whereHas('donHang', function($query) {
                $query->where('trang_thai', 'da_giao');
            })
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
        
        // Thống kê trạng thái đơn hàng với mapping sang tiếng Việt
        $statusMapping = [
            'cho_xac_nhan' => 'Chờ xác nhận',
            'cho_xu_ly' => 'Chờ xử lý',
            'da_giao' => 'Đã giao',
            'da_huy' => 'Đã hủy'
        ];
        
        $orderStatusRaw = DonHang::select('trang_thai', DB::raw('COUNT(*) as count'))
            ->groupBy('trang_thai')
            ->get();
        
        $orderStatus = $orderStatusRaw->map(function($item) use ($statusMapping) {
            return [
                'trang_thai' => $statusMapping[$item->trang_thai] ?? $item->trang_thai,
                'count' => $item->count
            ];
        });
        
        // Thống kê theo ngày (7 ngày gần nhất) - chỉ tính đơn đã giao
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->toDateString();
            
            // Revenue chỉ tính từ đơn đã giao
            $dayRevenue = DonHang::where('trang_thai', 'da_giao')
                ->whereDate('ngay_dat_hang', $dateString)
                ->sum('tong_tien');
            
            $dailyStats[] = [
                'date' => $date->format('d/m'),
                'orders' => DonHang::whereDate('ngay_dat_hang', $dateString)->count(),
                'revenue' => $dayRevenue,
                'users' => User::whereDate('ngay_tao', $dateString)->count()
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