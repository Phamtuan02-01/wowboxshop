<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DonHang;
use App\Models\ChiTietDonHang;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders.
     */
    public function index(Request $request)
    {
        $query = DonHang::with(['taiKhoan', 'chiTietDonHangs.sanPham']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ma_don_hang', 'LIKE', "%{$search}%")
                  ->orWhereHas('taiKhoan', function($userQuery) use ($search) {
                      $userQuery->where('ho_ten', 'LIKE', "%{$search}%")
                               ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('trang_thai', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('ngay_dat_hang', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('ngay_dat_hang', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'ngay_dat_hang');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $orders = $query->paginate(20)->appends($request->query());

        // Statistics
        $stats = [
            'total' => DonHang::count(),
            'pending' => DonHang::where('trang_thai', 'cho_xac_nhan')->count(),
            'processing' => 0, // Không dùng nữa
            'completed' => DonHang::where('trang_thai', 'da_giao')->count(),
            'cancelled' => DonHang::where('trang_thai', 'da_huy')->count(),
        ];

        // Revenue statistics (CHỈ tính đơn đã giao, KHÔNG tính đơn hủy)
        $stats['total_revenue'] = DonHang::where('trang_thai', 'da_giao')
            ->sum('tong_tien');
        $stats['monthly_revenue'] = DonHang::where('trang_thai', 'da_giao')
            ->whereMonth('ngay_dat_hang', now()->month)
            ->whereYear('ngay_dat_hang', now()->year)
            ->sum('tong_tien');

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = DonHang::with([
            'taiKhoan', 
            'chiTietDonHangs.sanPham'
        ])->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'trang_thai' => 'required|in:cho_xac_nhan,da_giao,da_huy',
            'ghi_chu' => 'nullable|string|max:500'
        ]);

        try {
            $order = DonHang::findOrFail($id);
            $oldStatus = $order->trang_thai_text;
            
            // Không cho phép cập nhật nếu đơn đã giao hoặc đã hủy
            if (in_array($order->trang_thai, ['da_giao', 'da_huy'])) {
                return back()->with('error', 'Không thể cập nhật đơn hàng đã giao hoặc đã hủy.');
            }
            
            $order->update([
                'trang_thai' => $request->trang_thai,
                'ghi_chu' => $request->ghi_chu,
                'ngay_cap_nhat' => now()
            ]);

            $newStatus = $order->fresh()->trang_thai_text;
            
            return redirect()->route('admin.orders.show', $order->ma_don_hang)
                ->with('success', "Cập nhật trạng thái đơn hàng từ '{$oldStatus}' thành '{$newStatus}' thành công!");
                
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Cancel an order.
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'ly_do_huy' => 'required|string|max:500'
        ]);

        try {
            $order = DonHang::findOrFail($id);
            
            if (in_array($order->trang_thai, ['da_giao', 'da_huy'])) {
                return back()->with('error', 'Không thể hủy đơn hàng ở trạng thái hiện tại.');
            }

            $order->update([
                'trang_thai' => 'da_huy',
                'ghi_chu' => 'Lý do hủy: ' . $request->ly_do_huy,
                'ngay_cap_nhat' => now()
            ]);

            // Lưu ý: Đơn hủy sẽ KHÔNG được tính vào doanh thu
            // Revenue queries chỉ tính trang_thai = 'da_giao'

            return redirect()->route('admin.orders.show', $order->ma_don_hang)
                ->with('success', 'Hủy đơn hàng thành công! Đơn hàng này sẽ không được tính vào doanh thu.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Get order statistics for dashboard.
     */
    public function getStatistics(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year
        
        $baseQuery = DonHang::query();
        
        switch ($period) {
            case 'day':
                $baseQuery->whereDate('ngay_dat_hang', today());
                break;
            case 'week':
                $baseQuery->whereBetween('ngay_dat_hang', [
                    now()->startOfWeek(),
                    now()->endOfWeek()
                ]);
                break;
            case 'month':
                $baseQuery->whereMonth('ngay_dat_hang', now()->month)
                         ->whereYear('ngay_dat_hang', now()->year);
                break;
            case 'year':
                $baseQuery->whereYear('ngay_dat_hang', now()->year);
                break;
        }

        $stats = [
            'total_orders' => $baseQuery->count(),
            'total_revenue' => $baseQuery->where('trang_thai', 'da_giao')->sum('tong_tien'),
            'pending_orders' => $baseQuery->where('trang_thai', 'cho_xac_nhan')->count(),
            'completed_orders' => $baseQuery->where('trang_thai', 'da_giao')->count(),
        ];

        return response()->json($stats);
    }

    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        try {
            $query = DonHang::with(['taiKhoan', 'chiTietDonHangs.sanPham']);

            // Apply filters
            if ($request->filled('status')) {
                $query->where('trang_thai', $request->status);
            }
            if ($request->filled('date_from')) {
                $query->whereDate('ngay_dat_hang', '>=', $request->date_from);
            }
            if ($request->filled('date_to')) {
                $query->whereDate('ngay_dat_hang', '<=', $request->date_to);
            }

            $orders = $query->get();

            $filename = 'danh-sach-don-hang-' . date('Y-m-d-H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($orders) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                // Add headers
                fputcsv($file, [
                    'Mã đơn hàng',
                    'Khách hàng',
                    'Email',
                    'Tổng tiền',
                    'Trạng thái',
                    'Ngày đặt',
                    'Ghi chú'
                ]);

                // Add data
                foreach ($orders as $order) {
                    fputcsv($file, [
                        $order->ma_don_hang,
                        $order->taiKhoan->ho_ten ?? 'N/A',
                        $order->taiKhoan->email ?? 'N/A',
                        number_format($order->tong_tien, 0, ',', '.') . ' VNĐ',
                        $order->trang_thai,
                        $order->ngay_dat_hang ? $order->ngay_dat_hang->format('d/m/Y H:i:s') : 'N/A',
                        $order->ghi_chu ?? ''
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