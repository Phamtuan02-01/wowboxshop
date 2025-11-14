@extends('layouts.app')

@section('title', 'Lịch sử đơn hàng')

@section('content')
<style>
    .orders-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .orders-header {
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(46, 125, 50, 0.3);
        margin-bottom: 2rem;
    }

    .orders-header h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 2rem;
        background: white;
        padding: 1rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .filter-tab {
        padding: 0.7rem 1.5rem;
        border: 2px solid #e0e0e0;
        background: white;
        color: #666;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        text-decoration: none;
    }

    .filter-tab:hover {
        border-color: #2E7D32;
        color: #2E7D32;
        background: #f0f8f0;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        color: white;
        border-color: #2E7D32;
    }

    .order-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        border-left: 5px solid #2E7D32;
    }

    .order-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }

    .order-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-id {
        font-weight: 600;
        color: #333;
        font-size: 1.1rem;
    }

    .order-date {
        color: #666;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .order-status {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-cho_xac_nhan {
        background: linear-gradient(135deg, #fff3cd, #ffe69c);
        color: #856404;
    }

    .status-da_xac_nhan {
        background: linear-gradient(135deg, #d1ecf1, #bee5eb);
        color: #0c5460;
    }

    .status-dang_giao {
        background: linear-gradient(135deg, #cce5ff, #b8daff);
        color: #004085;
    }

    .status-da_giao {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
    }

    .status-da_huy {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
    }

    .order-body {
        padding: 1.5rem;
    }

    .order-items {
        margin-bottom: 1rem;
    }

    .order-item {
        display: flex;
        gap: 1rem;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #e0e0e0;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.3rem;
    }

    .item-variant {
        color: #666;
        font-size: 0.9rem;
    }

    .item-quantity {
        color: #666;
        font-size: 0.9rem;
    }

    .item-price {
        font-weight: 600;
        color: #2E7D32;
        text-align: right;
    }

    .order-footer {
        padding: 1.5rem;
        background: #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .order-total {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2E7D32;
    }

    .order-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn {
        padding: 0.7rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
    }

    .btn-danger {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
    }

    .btn-secondary {
        background: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .cancel-timer {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.5rem 1rem;
        background: linear-gradient(135deg, #fff3cd, #ffe69c);
        color: #856404;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .empty-icon {
        font-size: 5rem;
        color: #e0e0e0;
        margin-bottom: 1rem;
    }

    .empty-text {
        color: #666;
        font-size: 1.2rem;
        margin-bottom: 1rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 2rem;
    }

    @media (max-width: 768px) {
        .orders-container {
            padding: 0 1rem;
        }

        .order-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .order-footer {
            flex-direction: column;
            align-items: flex-start;
        }

        .item-image {
            width: 60px;
            height: 60px;
        }
    }
</style>

<div class="orders-container">
    <div class="orders-header">
        <h1>
            <i class="fas fa-history"></i>
            Lịch sử đơn hàng
        </h1>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs">
        <a href="{{ route('orders.history') }}" class="filter-tab {{ !request('status') || request('status') == 'all' ? 'active' : '' }}">
            <i class="fas fa-list"></i> Tất cả
        </a>
        <a href="{{ route('orders.history', ['status' => 'cho_xac_nhan']) }}" class="filter-tab {{ request('status') == 'cho_xac_nhan' ? 'active' : '' }}">
            <i class="fas fa-clock"></i> Chờ xác nhận
        </a>
        <a href="{{ route('orders.history', ['status' => 'da_xac_nhan']) }}" class="filter-tab {{ request('status') == 'da_xac_nhan' ? 'active' : '' }}">
            <i class="fas fa-check"></i> Đã xác nhận
        </a>
        <a href="{{ route('orders.history', ['status' => 'dang_giao']) }}" class="filter-tab {{ request('status') == 'dang_giao' ? 'active' : '' }}">
            <i class="fas fa-shipping-fast"></i> Đang giao
        </a>
        <a href="{{ route('orders.history', ['status' => 'da_giao']) }}" class="filter-tab {{ request('status') == 'da_giao' ? 'active' : '' }}">
            <i class="fas fa-check-circle"></i> Hoàn thành
        </a>
        <a href="{{ route('orders.history', ['status' => 'da_huy']) }}" class="filter-tab {{ request('status') == 'da_huy' ? 'active' : '' }}">
            <i class="fas fa-times-circle"></i> Đã hủy
        </a>
    </div>

    @if($orders->count() > 0)
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-header">
                <div>
                    <div class="order-id">
                        <i class="fas fa-receipt"></i> Đơn hàng #{{ $order->ma_don_hang }}
                    </div>
                    <div class="order-date">
                        <i class="fas fa-calendar"></i>
                        {{ $order->ngay_dat_hang->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div>
                    @php
                        $statusLabels = [
                            'cho_xac_nhan' => 'Chờ xác nhận',
                            'da_xac_nhan' => 'Đã xác nhận',
                            'dang_giao' => 'Đang giao hàng',
                            'da_giao' => 'Đã giao hàng',
                            'da_huy' => 'Đã hủy'
                        ];
                    @endphp
                    <span class="order-status status-{{ $order->trang_thai }}">
                        @if($order->trang_thai == 'cho_xac_nhan')
                            <i class="fas fa-clock"></i>
                        @elseif($order->trang_thai == 'da_xac_nhan')
                            <i class="fas fa-check"></i>
                        @elseif($order->trang_thai == 'dang_giao')
                            <i class="fas fa-shipping-fast"></i>
                        @elseif($order->trang_thai == 'da_giao')
                            <i class="fas fa-check-circle"></i>
                        @else
                            <i class="fas fa-times-circle"></i>
                        @endif
                        {{ $statusLabels[$order->trang_thai] ?? $order->trang_thai }}
                    </span>
                </div>
            </div>

            <div class="order-body">
                @if($order->trang_thai === 'da_huy')
                    <div class="alert" style="background: linear-gradient(135deg, #f8d7da, #f5c6cb); border-left: 4px solid #dc3545; padding: 1rem; margin-bottom: 1rem; border-radius: 10px;">
                        <div style="display: flex; align-items: start; gap: 0.8rem;">
                            <i class="fas fa-exclamation-circle" style="color: #721c24; font-size: 1.5rem; margin-top: 0.2rem;"></i>
                            <div style="flex: 1;">
                                @if($order->ly_do_huy)
                                    <strong style="color: #721c24; display: block; margin-bottom: 0.3rem;">
                                        <i class="fas fa-user"></i> Lý do khách hàng hủy đơn:
                                    </strong>
                                    <p style="color: #721c24; margin: 0;">{{ $order->ly_do_huy }}</p>
                                @elseif($order->ghi_chu)
                                    <strong style="color: #721c24; display: block; margin-bottom: 0.3rem;">
                                        <i class="fas fa-store"></i> Lý do quán hủy đơn:
                                    </strong>
                                    <p style="color: #721c24; margin: 0;">{{ $order->ghi_chu }}</p>
                                @else
                                    <strong style="color: #721c24;">
                                        <i class="fas fa-ban"></i> Đơn hàng đã bị hủy
                                    </strong>
                                @endif
                                @if($order->ngay_huy)
                                    <small style="color: #721c24; opacity: 0.8; display: block; margin-top: 0.5rem;">
                                        <i class="fas fa-clock"></i> Thời gian hủy: {{ $order->ngay_huy->format('d/m/Y H:i') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="order-items">
                    @foreach($order->chiTietDonHangs->take(3) as $item)
                    <div class="order-item">
                        <img src="{{ asset('images/products/' . $item->sanPham->hinh_anh) }}" 
                             alt="{{ $item->sanPham->ten_san_pham }}" 
                             class="item-image"
                             onerror="this.src='{{ asset('images/products/default-product.png') }}'">
                        <div class="item-info">
                            <div class="item-name">{{ $item->sanPham->ten_san_pham }}</div>
                            <div class="item-variant">{{ $item->bienThe->ten_bien_the }}</div>
                            <div class="item-quantity">Số lượng: {{ $item->so_luong }}</div>
                        </div>
                        <div class="item-price">
                            {{ number_format($item->gia_tai_thoi_diem_mua * $item->so_luong, 0, ',', '.') }}₫
                        </div>
                    </div>
                    @endforeach
                    @if($order->chiTietDonHangs->count() > 3)
                    <div style="text-align: center; padding: 0.5rem; color: #666;">
                        <i class="fas fa-ellipsis-h"></i> Và {{ $order->chiTietDonHangs->count() - 3 }} sản phẩm khác
                    </div>
                    @endif
                </div>
            </div>

            <div class="order-footer">
                <div>
                    <div style="color: #666; font-size: 0.9rem;">Tổng tiền:</div>
                    <div class="order-total">{{ number_format($order->tong_tien, 0, ',', '.') }}₫</div>
                    
                    @if($order->can_cancel && $order->time_left)
                    <div class="cancel-timer" data-seconds="{{ $order->time_left }}" data-order-id="{{ $order->ma_don_hang }}">
                        <i class="fas fa-stopwatch"></i>
                        <span class="timer-display"></span>
                    </div>
                    @endif
                </div>

                <div class="order-actions">
                    <a href="{{ route('orders.show', $order->ma_don_hang) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i> Xem chi tiết
                    </a>
                    @if($order->can_cancel)
                    <button type="button" class="btn btn-danger" onclick="showCancelModal({{ $order->ma_don_hang }})">
                        <i class="fas fa-times"></i> Hủy đơn
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="pagination">
            {{ $orders->links() }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="empty-text">Chưa có đơn hàng nào</div>
            <a href="{{ route('dat-mon.index') }}" class="btn btn-primary">
                <i class="fas fa-shopping-cart"></i> Đặt món ngay
            </a>
        </div>
    @endif
</div>

<!-- Cancel Order Modal -->
<div id="cancelModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; justify-content: center; align-items: center;">
    <div style="background: white; border-radius: 20px; padding: 2rem; max-width: 500px; width: 90%; max-height: 90vh; overflow-y: auto;">
        <h3 style="margin: 0 0 1.5rem 0; color: #2E7D32; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-exclamation-triangle"></i>
            Xác nhận hủy đơn hàng
        </h3>
        
        <form id="cancelForm" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: #333;">
                    Vui lòng chọn lý do hủy đơn: <span style="color: red;">*</span>
                </label>
                <select name="cancel_reason" class="form-control" required style="width: 100%; padding: 0.8rem; border: 2px solid #e0e0e0; border-radius: 10px; margin-bottom: 0.5rem;">
                    <option value="">-- Chọn lý do --</option>
                    <option value="Đổi ý không muốn mua nữa">Đổi ý không muốn mua nữa</option>
                    <option value="Tìm thấy sản phẩm rẻ hơn">Tìm thấy sản phẩm rẻ hơn</option>
                    <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                    <option value="Đặt nhầm địa chỉ">Đặt nhầm địa chỉ</option>
                    <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
                    <option value="Không có nhu cầu sử dụng nữa">Không có nhu cầu sử dụng nữa</option>
                    <option value="Đặt trùng đơn hàng">Đặt trùng đơn hàng</option>
                    <option value="Muốn thay đổi sản phẩm">Muốn thay đổi sản phẩm</option>
                    <option value="Muốn thay đổi phương thức thanh toán">Muốn thay đổi phương thức thanh toán</option>
                    <option value="Khác">Khác (ghi rõ bên dưới)</option>
                </select>
                
                <textarea name="cancel_reason_other" 
                          id="cancelReasonOther"
                          placeholder="Nhập lý do khác (nếu chọn 'Khác')" 
                          style="width: 100%; padding: 0.8rem; border: 2px solid #e0e0e0; border-radius: 10px; min-height: 100px; display: none;"
                          maxlength="500"></textarea>
            </div>

            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="hideCancelModal()">
                    <i class="fas fa-times"></i> Đóng
                </button>
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-check"></i> Xác nhận hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle cancel reason dropdown
    document.querySelector('select[name="cancel_reason"]').addEventListener('change', function() {
        const otherInput = document.getElementById('cancelReasonOther');
        if (this.value === 'Khác') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            otherInput.value = '';
        }
    });

    function showCancelModal(orderId) {
        const modal = document.getElementById('cancelModal');
        const form = document.getElementById('cancelForm');
        const baseUrl = '{{ route("orders.cancel", ":id") }}';
        form.action = baseUrl.replace(':id', orderId);
        modal.style.display = 'flex';
    }

    function hideCancelModal() {
        const modal = document.getElementById('cancelModal');
        modal.style.display = 'none';
    }

    // Timer countdown
    function updateTimers() {
        document.querySelectorAll('.cancel-timer').forEach(timer => {
            let seconds = parseInt(timer.dataset.seconds);
            const orderId = timer.dataset.orderId;
            const display = timer.querySelector('.timer-display');

            if (seconds > 0) {
                const minutes = Math.floor(seconds / 60);
                const secs = seconds % 60;
                display.textContent = `Còn ${minutes}:${secs.toString().padStart(2, '0')} để hủy`;
                
                timer.dataset.seconds = seconds - 1;
            } else {
                timer.innerHTML = '<i class="fas fa-info-circle"></i> Hết thời gian hủy';
                timer.style.background = '#f8d7da';
                timer.style.color = '#721c24';
                
                // Hide cancel button
                const cancelBtn = timer.closest('.order-card').querySelector('.btn-danger');
                if (cancelBtn) {
                    cancelBtn.style.display = 'none';
                }
            }
        });
    }

    // Update timers every second
    setInterval(updateTimers, 1000);
    updateTimers(); // Initial call
</script>
@endsection
