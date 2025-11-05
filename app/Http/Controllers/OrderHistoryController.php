<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DonHang;
use Carbon\Carbon;

class OrderHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = DonHang::where('ma_tai_khoan', Auth::id())
                        ->with(['chiTietDonHangs.sanPham', 'chiTietDonHangs.bienThe'])
                        ->orderBy('ngay_dat_hang', 'desc');

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('trang_thai', $request->status);
        }

        $orders = $query->paginate(10);

        // Calculate if orders can be cancelled
        foreach ($orders as $order) {
            $order->can_cancel = $this->canCancelOrder($order);
            $order->time_left = $this->getTimeLeftToCancel($order);
        }

        return view('orders.history', compact('orders'));
    }

    public function show($id)
    {
        $order = DonHang::where('ma_tai_khoan', Auth::id())
                        ->where('ma_don_hang', $id)
                        ->with(['chiTietDonHangs.sanPham', 'chiTietDonHangs.bienThe', 'khuyenMai'])
                        ->firstOrFail();

        $order->can_cancel = $this->canCancelOrder($order);
        $order->time_left = $this->getTimeLeftToCancel($order);

        return view('orders.detail', compact('order'));
    }

    public function cancel(Request $request, $id)
    {
        $order = DonHang::where('ma_tai_khoan', Auth::id())
                        ->where('ma_don_hang', $id)
                        ->firstOrFail();

        // Check if order can be cancelled
        if (!$this->canCancelOrder($order)) {
            return back()->with('error', 'Không thể hủy đơn hàng này!');
        }

        $validated = $request->validate([
            'cancel_reason' => 'required|string',
            'cancel_reason_other' => 'nullable|string|max:500'
        ], [
            'cancel_reason.required' => 'Vui lòng chọn lý do hủy đơn hàng',
        ]);

        $cancelReason = $validated['cancel_reason'];
        if ($cancelReason === 'Khác' && !empty($validated['cancel_reason_other'])) {
            $cancelReason = $validated['cancel_reason_other'];
        }

        // Update order status
        $order->update([
            'trang_thai' => 'da_huy',
            'ly_do_huy' => $cancelReason,
            'ngay_huy' => Carbon::now()
        ]);

        // Restore stock for cancelled order
        foreach ($order->chiTietDonHangs as $chiTiet) {
            $chiTiet->bienThe->increment('so_luong_ton', $chiTiet->so_luong);
        }

        return redirect()->route('orders.history')
                        ->with('success', 'Đã hủy đơn hàng thành công!');
    }

    private function canCancelOrder($order)
    {
        // Only can cancel if status is 'cho_xac_nhan'
        if ($order->trang_thai !== 'cho_xac_nhan') {
            return false;
        }

        // Check if within 3 minutes from order time
        $orderTime = Carbon::parse($order->ngay_dat_hang);
        $now = Carbon::now();
        $minutesPassed = $now->diffInMinutes($orderTime);

        return $minutesPassed <= 3;
    }

    private function getTimeLeftToCancel($order)
    {
        if ($order->trang_thai !== 'cho_xac_nhan') {
            return null;
        }

        $orderTime = Carbon::parse($order->ngay_dat_hang);
        $now = Carbon::now();
        $minutesPassed = $now->diffInMinutes($orderTime);
        $secondsPassed = $now->diffInSeconds($orderTime);

        if ($minutesPassed >= 3) {
            return null;
        }

        $secondsLeft = (3 * 60) - $secondsPassed;
        return $secondsLeft;
    }
}
