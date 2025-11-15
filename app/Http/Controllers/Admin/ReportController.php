<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\SanPham;
use App\Models\ChiTietDonHang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        // Lấy khoảng thời gian từ request hoặc mặc định là tháng hiện tại
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $period = $request->input('period', 'day'); // day, week, month
        
        // Chuyển đổi sang Carbon
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Tổng quan doanh thu
        $overview = $this->getRevenueOverview($start, $end);
        
        // Doanh thu theo thời gian
        $revenueByTime = $this->getRevenueByTime($start, $end, $period);
        
        // Top sản phẩm bán chạy
        $topProducts = $this->getTopProducts($start, $end, 10);
        
        // Doanh thu theo danh mục
        $revenueByCategory = $this->getRevenueByCategory($start, $end);
        
        // So sánh với kỳ trước
        $comparison = $this->getComparison($start, $end);
        
        return view('admin.reports.revenue', compact(
            'overview',
            'revenueByTime',
            'topProducts',
            'revenueByCategory',
            'comparison',
            'startDate',
            'endDate',
            'period'
        ));
    }
    
    private function getRevenueOverview($start, $end)
    {
        $orders = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->get();
        
        return [
            'total_revenue' => $orders->sum('tong_tien'),
            'total_orders' => $orders->count(),
            'total_products_sold' => ChiTietDonHang::whereIn('ma_don_hang', $orders->pluck('ma_don_hang'))
                ->sum('so_luong'),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('tong_tien') / $orders->count() : 0,
        ];
    }
    
    private function getRevenueByTime($start, $end, $period)
    {
        $orders = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->get();
        
        $data = [];
        
        if ($period === 'day') {
            // Nhóm theo ngày
            $data = $orders->groupBy(function($order) {
                return Carbon::parse($order->ngay_dat_hang)->format('Y-m-d');
            })->map(function($dayOrders) {
                return [
                    'revenue' => $dayOrders->sum('tong_tien'),
                    'orders' => $dayOrders->count(),
                ];
            });
        } elseif ($period === 'week') {
            // Nhóm theo tuần
            $data = $orders->groupBy(function($order) {
                return Carbon::parse($order->ngay_dat_hang)->weekOfYear;
            })->map(function($weekOrders) {
                return [
                    'revenue' => $weekOrders->sum('tong_tien'),
                    'orders' => $weekOrders->count(),
                ];
            });
        } elseif ($period === 'month') {
            // Nhóm theo tháng
            $data = $orders->groupBy(function($order) {
                return Carbon::parse($order->ngay_dat_hang)->format('Y-m');
            })->map(function($monthOrders) {
                return [
                    'revenue' => $monthOrders->sum('tong_tien'),
                    'orders' => $monthOrders->count(),
                ];
            });
        }
        
        return $data;
    }
    
    private function getTopProducts($start, $end, $limit = 10)
    {
        $orderIds = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->pluck('ma_don_hang');
        
        $topProducts = ChiTietDonHang::whereIn('ma_don_hang', $orderIds)
            ->select('ma_san_pham', DB::raw('SUM(so_luong) as total_quantity'), DB::raw('SUM(so_luong * gia_tai_thoi_diem_mua) as total_revenue'))
            ->groupBy('ma_san_pham')
            ->orderByDesc('total_quantity')
            ->limit($limit)
            ->with('sanPham')
            ->get();
        
        return $topProducts;
    }
    
    private function getRevenueByCategory($start, $end)
    {
        $orderIds = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->pluck('ma_don_hang');
        
        $revenueByCategory = ChiTietDonHang::whereIn('ma_don_hang', $orderIds)
            ->join('san_pham', 'chi_tiet_don_hang.ma_san_pham', '=', 'san_pham.ma_san_pham')
            ->join('danh_muc', 'san_pham.ma_danh_muc', '=', 'danh_muc.ma_danh_muc')
            ->select('danh_muc.ma_danh_muc', 'danh_muc.ten_danh_muc', 
                     DB::raw('SUM(chi_tiet_don_hang.so_luong) as total_quantity'),
                     DB::raw('SUM(chi_tiet_don_hang.so_luong * chi_tiet_don_hang.gia_tai_thoi_diem_mua) as total_revenue'))
            ->groupBy('danh_muc.ma_danh_muc', 'danh_muc.ten_danh_muc')
            ->orderByDesc('total_revenue')
            ->get();
        
        return $revenueByCategory;
    }
    
    private function getComparison($start, $end)
    {
        // Tính khoảng thời gian
        $duration = $end->diffInDays($start) + 1;
        
        // Kỳ trước
        $previousStart = $start->copy()->subDays($duration);
        $previousEnd = $start->copy()->subDay();
        
        // Doanh thu kỳ hiện tại
        $currentRevenue = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->sum('tong_tien');
        
        // Doanh thu kỳ trước
        $previousRevenue = DonHang::whereBetween('ngay_dat_hang', [$previousStart, $previousEnd])
            ->where('trang_thai', 'da_giao')
            ->sum('tong_tien');
        
        // Tính % thay đổi
        $changePercent = 0;
        if ($previousRevenue > 0) {
            $changePercent = (($currentRevenue - $previousRevenue) / $previousRevenue) * 100;
        }
        
        return [
            'current_revenue' => $currentRevenue,
            'previous_revenue' => $previousRevenue,
            'change_percent' => $changePercent,
            'is_increase' => $changePercent >= 0,
        ];
    }
    
    /**
     * Export revenue report to CSV.
     */
    public function exportRevenue(Request $request)
    {
        try {
            // Lấy khoảng thời gian từ request hoặc mặc định là tháng hiện tại
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            $period = $request->input('period', 'day');
            
            // Chuyển đổi sang Carbon
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            
            // Lấy dữ liệu tổng quan
            $orders = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
                ->where('trang_thai', 'da_giao')
                ->get();
            
            $totalRevenue = $orders->sum('tong_tien');
            $totalOrders = $orders->count();
            $totalProducts = ChiTietDonHang::whereIn('ma_don_hang', $orders->pluck('ma_don_hang'))
                ->sum('so_luong');
            $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
            
            // Lấy top sản phẩm
            $topProducts = $this->getTopProducts($start, $end, 20);
            
            // Lấy doanh thu theo danh mục
            $revenueByCategory = $this->getRevenueByCategory($start, $end);
            
            // Lấy doanh thu theo thời gian
            $revenueByTime = $this->getRevenueByTime($start, $end, $period);
            
            // Tạo file CSV
            $filename = 'bao_cao_doanh_thu_' . $startDate . '_' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($startDate, $endDate, $totalRevenue, $totalOrders, $avgOrderValue, $totalProducts, $topProducts, $revenueByCategory, $revenueByTime) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // ===== PHẦN 1: TỔNG QUAN =====
                fputcsv($file, ['BÁO CÁO DOANH THU']);
                fputcsv($file, ['Từ ngày:', date('d/m/Y', strtotime($startDate)), 'Đến ngày:', date('d/m/Y', strtotime($endDate))]);
                fputcsv($file, []);
                
                fputcsv($file, ['CHỈ SỐ TỔNG QUAN']);
                fputcsv($file, ['Tổng doanh thu:', number_format($totalRevenue, 0, ',', '.') . ' VNĐ']);
                fputcsv($file, ['Tổng đơn hàng:', number_format($totalOrders)]);
                fputcsv($file, ['Giá trị đơn TB:', number_format($avgOrderValue, 0, ',', '.') . ' VNĐ']);
                fputcsv($file, ['Sản phẩm đã bán:', number_format($totalProducts)]);
                fputcsv($file, []);
                fputcsv($file, []);
                
                // ===== PHẦN 2: TOP SẢN PHẨM BÁN CHẠY =====
                fputcsv($file, ['TOP SẢN PHẨM BÁN CHẠY']);
                fputcsv($file, ['STT', 'Tên sản phẩm', 'Số lượng bán', 'Doanh thu']);
                
                foreach ($topProducts as $index => $product) {
                    fputcsv($file, [
                        $index + 1,
                        $product->ten_san_pham ?? $product->sanPham->ten_san_pham ?? 'N/A',
                        number_format($product->total_quantity),
                        number_format($product->total_revenue, 0, ',', '.') . ' VNĐ'
                    ]);
                }
                fputcsv($file, []);
                fputcsv($file, []);
                
                // ===== PHẦN 3: DOANH THU THEO DANH MỤC =====
                fputcsv($file, ['DOANH THU THEO DANH MỤC']);
                fputcsv($file, ['Danh mục', 'Doanh thu', 'Số lượng', 'Tỷ lệ %']);
                
                $totalCategoryRevenue = $revenueByCategory->sum('total_revenue');
                foreach ($revenueByCategory as $category) {
                    $percentage = $totalCategoryRevenue > 0 ? ($category->total_revenue / $totalCategoryRevenue) * 100 : 0;
                    fputcsv($file, [
                        $category->ten_danh_muc,
                        number_format($category->total_revenue, 0, ',', '.') . ' VNĐ',
                        number_format($category->total_quantity),
                        number_format($percentage, 2) . '%'
                    ]);
                }
                fputcsv($file, []);
                fputcsv($file, []);
                
                // ===== PHẦN 4: DOANH THU THEO THỜI GIAN =====
                fputcsv($file, ['DOANH THU THEO THỜI GIAN']);
                fputcsv($file, ['Thời gian', 'Doanh thu', 'Số đơn hàng']);
                
                foreach ($revenueByTime as $date => $values) {
                    fputcsv($file, [
                        $date,
                        number_format($values['revenue'], 0, ',', '.') . ' VNĐ',
                        number_format($values['orders'])
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra khi xuất file: ' . $e->getMessage());
        }
    }
    
    /**
     * Trang thống kê bán hàng
     */
    public function sales(Request $request)
    {
        // Lấy khoảng thời gian từ request hoặc mặc định là tháng hiện tại
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Chuyển đổi sang Carbon
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
        
        // Tổng quan bán hàng
        $overview = $this->getSalesOverview($start, $end);
        
        // Top sản phẩm bán chạy
        $topProducts = $this->getTopSellingProducts($start, $end, 10);
        
        // Sản phẩm bán chậm
        $slowProducts = $this->getSlowSellingProducts($start, $end, 10);
        
        // Thống kê theo danh mục
        $categoryStats = $this->getCategoryStats($start, $end);
        
        // Thống kê theo trạng thái đơn hàng
        $orderStatusStats = $this->getOrderStatusStats($start, $end);
        
        // Doanh số theo ngày (cho biểu đồ)
        $dailySales = $this->getDailySales($start, $end);
        
        // Sản phẩm sắp hết hàng
        $lowStockProducts = $this->getLowStockProducts();
        
        return view('admin.reports.sales', compact(
            'overview',
            'topProducts',
            'slowProducts',
            'categoryStats',
            'orderStatusStats',
            'dailySales',
            'lowStockProducts',
            'startDate',
            'endDate'
        ));
    }
    
    private function getSalesOverview($start, $end)
    {
        // Tất cả đơn hàng trong khoảng thời gian
        $allOrders = DonHang::whereBetween('ngay_dat_hang', [$start, $end])->get();
        
        // Đơn hàng đã giao
        $completedOrders = $allOrders->where('trang_thai', 'da_giao');
        
        // Đơn hàng đang xử lý
        $processingOrders = $allOrders->whereIn('trang_thai', ['cho_xac_nhan', 'dang_xu_ly', 'dang_giao']);
        
        // Đơn hàng bị hủy
        $cancelledOrders = $allOrders->where('trang_thai', 'da_huy');
        
        // Tính tổng sản phẩm đã bán (chỉ đơn đã giao)
        $totalProductsSold = ChiTietDonHang::whereIn('ma_don_hang', $completedOrders->pluck('ma_don_hang'))
            ->sum('so_luong');
        
        // Tính tỷ lệ chuyển đổi
        $conversionRate = $allOrders->count() > 0 
            ? ($completedOrders->count() / $allOrders->count()) * 100 
            : 0;
        
        return [
            'total_orders' => $allOrders->count(),
            'completed_orders' => $completedOrders->count(),
            'processing_orders' => $processingOrders->count(),
            'cancelled_orders' => $cancelledOrders->count(),
            'total_revenue' => $completedOrders->sum('tong_tien'),
            'total_products_sold' => $totalProductsSold,
            'conversion_rate' => $conversionRate,
            'average_order_value' => $completedOrders->count() > 0 
                ? $completedOrders->sum('tong_tien') / $completedOrders->count() 
                : 0,
        ];
    }
    
    private function getTopSellingProducts($start, $end, $limit = 10)
    {
        $orderIds = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->pluck('ma_don_hang');
        
        return ChiTietDonHang::whereIn('ma_don_hang', $orderIds)
            ->select('ma_san_pham', 
                     DB::raw('SUM(so_luong) as total_sold'),
                     DB::raw('SUM(so_luong * gia_tai_thoi_diem_mua) as total_revenue'))
            ->groupBy('ma_san_pham')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->with('sanPham')
            ->get();
    }
    
    private function getSlowSellingProducts($start, $end, $limit = 10)
    {
        $orderIds = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->pluck('ma_don_hang');
        
        // Lấy tất cả sản phẩm đã có bán hàng
        $soldProducts = ChiTietDonHang::whereIn('ma_don_hang', $orderIds)
            ->select('ma_san_pham', 
                     DB::raw('SUM(so_luong) as total_sold'),
                     DB::raw('SUM(so_luong * gia_tai_thoi_diem_mua) as total_revenue'))
            ->groupBy('ma_san_pham')
            ->orderBy('total_sold', 'asc')
            ->limit($limit)
            ->with('sanPham')
            ->get();
        
        return $soldProducts;
    }
    
    private function getCategoryStats($start, $end)
    {
        $orderIds = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->pluck('ma_don_hang');
        
        return ChiTietDonHang::whereIn('ma_don_hang', $orderIds)
            ->join('san_pham', 'chi_tiet_don_hang.ma_san_pham', '=', 'san_pham.ma_san_pham')
            ->join('danh_muc', 'san_pham.ma_danh_muc', '=', 'danh_muc.ma_danh_muc')
            ->select('danh_muc.ma_danh_muc', 
                     'danh_muc.ten_danh_muc',
                     DB::raw('SUM(chi_tiet_don_hang.so_luong) as total_quantity'),
                     DB::raw('SUM(chi_tiet_don_hang.so_luong * chi_tiet_don_hang.gia_tai_thoi_diem_mua) as total_revenue'),
                     DB::raw('COUNT(DISTINCT chi_tiet_don_hang.ma_san_pham) as product_count'))
            ->groupBy('danh_muc.ma_danh_muc', 'danh_muc.ten_danh_muc')
            ->orderByDesc('total_revenue')
            ->get();
    }
    
    private function getOrderStatusStats($start, $end)
    {
        $orders = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->select('trang_thai', DB::raw('COUNT(*) as count'), DB::raw('SUM(tong_tien) as revenue'))
            ->groupBy('trang_thai')
            ->get();
        
        $statusMap = [
            'cho_xac_nhan' => 'Chờ xác nhận',
            'dang_xu_ly' => 'Đang xử lý',
            'dang_giao' => 'Đang giao',
            'da_giao' => 'Đã giao',
            'da_huy' => 'Đã hủy',
        ];
        
        return $orders->map(function($item) use ($statusMap) {
            $item->status_name = $statusMap[$item->trang_thai] ?? $item->trang_thai;
            return $item;
        });
    }
    
    private function getDailySales($start, $end)
    {
        $orders = DonHang::whereBetween('ngay_dat_hang', [$start, $end])
            ->where('trang_thai', 'da_giao')
            ->get();
        
        return $orders->groupBy(function($order) {
            return Carbon::parse($order->ngay_dat_hang)->format('Y-m-d');
        })->map(function($dayOrders) {
            return [
                'orders' => $dayOrders->count(),
                'revenue' => $dayOrders->sum('tong_tien'),
                'products' => ChiTietDonHang::whereIn('ma_don_hang', $dayOrders->pluck('ma_don_hang'))
                    ->sum('so_luong'),
            ];
        })->sortKeys();
    }
    
    private function getLowStockProducts($limit = 10)
    {
        // Lấy sản phẩm có biến thể sắp hết hàng (tồn kho <= 10)
        return SanPham::where('loai_san_pham', 'product')
            ->whereHas('bienThes', function($query) {
                $query->where('so_luong_ton', '<=', 10)
                      ->where('trang_thai', true);
            })
            ->with(['bienThes' => function($query) {
                $query->where('so_luong_ton', '<=', 10)
                      ->where('trang_thai', true)
                      ->orderBy('so_luong_ton', 'asc');
            }])
            ->limit($limit)
            ->get();
    }
    
    /**
     * Export sales report to CSV
     */
    public function exportSales(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
            
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
            
            // Lấy dữ liệu
            $overview = $this->getSalesOverview($start, $end);
            $topProducts = $this->getTopSellingProducts($start, $end, 20);
            $categoryStats = $this->getCategoryStats($start, $end);
            $orderStatusStats = $this->getOrderStatusStats($start, $end);
            
            $filename = 'thong_ke_ban_hang_' . $startDate . '_' . $endDate . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($startDate, $endDate, $overview, $topProducts, $categoryStats, $orderStatusStats) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // TỔNG QUAN
                fputcsv($file, ['THỐNG KÊ BÁN HÀNG']);
                fputcsv($file, ['Từ ngày:', date('d/m/Y', strtotime($startDate)), 'Đến ngày:', date('d/m/Y', strtotime($endDate))]);
                fputcsv($file, []);
                
                fputcsv($file, ['CHỈ SỐ TỔNG QUAN']);
                fputcsv($file, ['Tổng đơn hàng:', number_format($overview['total_orders'])]);
                fputcsv($file, ['Đơn hoàn thành:', number_format($overview['completed_orders'])]);
                fputcsv($file, ['Đơn đang xử lý:', number_format($overview['processing_orders'])]);
                fputcsv($file, ['Đơn đã hủy:', number_format($overview['cancelled_orders'])]);
                fputcsv($file, ['Tổng doanh thu:', number_format($overview['total_revenue'], 0, ',', '.') . ' VNĐ']);
                fputcsv($file, ['Sản phẩm đã bán:', number_format($overview['total_products_sold'])]);
                fputcsv($file, ['Tỷ lệ chuyển đổi:', number_format($overview['conversion_rate'], 2) . '%']);
                fputcsv($file, ['Giá trị đơn TB:', number_format($overview['average_order_value'], 0, ',', '.') . ' VNĐ']);
                fputcsv($file, []);
                fputcsv($file, []);
                
                // TOP SẢN PHẨM
                fputcsv($file, ['TOP SẢN PHẨM BÁN CHẠY']);
                fputcsv($file, ['STT', 'Tên sản phẩm', 'Số lượng bán', 'Doanh thu']);
                foreach ($topProducts as $index => $product) {
                    fputcsv($file, [
                        $index + 1,
                        $product->sanPham->ten_san_pham ?? 'N/A',
                        number_format($product->total_sold),
                        number_format($product->total_revenue, 0, ',', '.') . ' VNĐ'
                    ]);
                }
                fputcsv($file, []);
                fputcsv($file, []);
                
                // THỐNG KÊ DANH MỤC
                fputcsv($file, ['THỐNG KÊ THEO DANH MỤC']);
                fputcsv($file, ['Danh mục', 'Số lượng bán', 'Doanh thu', 'Số sản phẩm']);
                foreach ($categoryStats as $category) {
                    fputcsv($file, [
                        $category->ten_danh_muc,
                        number_format($category->total_quantity),
                        number_format($category->total_revenue, 0, ',', '.') . ' VNĐ',
                        number_format($category->product_count)
                    ]);
                }
                fputcsv($file, []);
                fputcsv($file, []);
                
                // TRẠNG THÁI ĐỐN HÀNG
                fputcsv($file, ['THỐNG KÊ TRẠNG THÁI ĐƠN HÀNG']);
                fputcsv($file, ['Trạng thái', 'Số lượng', 'Doanh thu']);
                foreach ($orderStatusStats as $status) {
                    fputcsv($file, [
                        $status->status_name,
                        number_format($status->count),
                        number_format($status->revenue, 0, ',', '.') . ' VNĐ'
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
