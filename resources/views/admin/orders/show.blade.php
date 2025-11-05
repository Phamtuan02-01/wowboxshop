@extends('layouts.admin')

@section('title', 'Chi tiết Đơn hàng #' . $order->ma_don_hang)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Đơn hàng</a></li>
    <li class="breadcrumb-item active">Chi tiết #{{ $order->ma_don_hang }}</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Đơn hàng #{{ $order->ma_don_hang }}</h1>
                <p class="text-muted">
                    <i class="fas fa-calendar"></i> 
                    Đặt lúc {{ $order->ngay_dat_hang ? $order->ngay_dat_hang->format('d/m/Y H:i:s') : ($order->ngay_tao ? $order->ngay_tao->format('d/m/Y H:i:s') : 'Không có') }}
                    @if($order->ngay_cap_nhat)
                        • Cập nhật {{ $order->ngay_cap_nhat->diffForHumans() }}
                    @endif
                </p>
            </div>
            <div class="header-actions">
                @if(!in_array($order->trang_thai, ['da_giao', 'da_huy']))
                <div class="dropdown me-2">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-edit"></i> Cập nhật trạng thái
                    </button>
                    <ul class="dropdown-menu">
                        @if($order->trang_thai === 'cho_xu_ly')
                            <li><a class="dropdown-item" href="#" onclick="updateOrderStatus('da_giao')">
                                <i class="fas fa-check text-success"></i> Đã giao
                            </a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="cancelOrder()">
                            <i class="fas fa-times"></i> Hủy đơn hàng
                        </a></li>
                    </ul>
                </div>
                @endif
                <button class="btn btn-outline-primary" onclick="printOrder()">
                    <i class="fas fa-print"></i> In đơn hàng
                </button>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-md-8">
            <!-- Order Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Trạng thái đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="order-status-timeline">
                        @php
                            $statuses = [
                                'cho_xu_ly' => ['label' => 'Chờ xử lý', 'icon' => 'clock'],
                                'da_giao' => ['label' => 'Đã giao', 'icon' => 'check-circle'],
                            ];
                            $isCancelled = $order->trang_thai === 'da_huy';
                        @endphp
                        
                        @if($isCancelled)
                            <div class="alert alert-danger">
                                <i class="fas fa-times-circle"></i>
                                <strong>Đơn hàng đã bị hủy</strong>
                                @if($order->ghi_chu)
                                    <br><small>Lý do: {{ $order->ghi_chu }}</small>
                                @endif
                            </div>
                        @else
                            <div class="status-timeline">
                                @foreach($statuses as $statusCode => $statusInfo)
                                    @php
                                        $isCompleted = false;
                                        if ($statusCode === 'cho_xu_ly') {
                                            $isCompleted = true; // Always completed if order exists
                                        } elseif ($statusCode === 'da_giao') {
                                            $isCompleted = ($order->trang_thai === 'da_giao');
                                        }
                                    @endphp
                                    <div class="status-item {{ $isCompleted ? 'completed' : '' }}">
                                        <div class="status-icon">
                                            <i class="fas fa-{{ $statusInfo['icon'] }}"></i>
                                        </div>
                                        <div class="status-content">
                                            <h6>{{ $statusInfo['label'] }}</h6>
                                            @if($statusCode === $order->trang_thai && $order->ngay_cap_nhat)
                                                <small class="text-muted">{{ $order->ngay_cap_nhat->format('d/m/Y H:i') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    @if($order->ghi_chu && !$isCancelled)
                        <div class="mt-3">
                            <strong>Ghi chú:</strong>
                            <p class="mb-0">{{ $order->ghi_chu }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart"></i> Sản phẩm đã đặt
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->chiTietDonHangs as $detail)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($detail->sanPham && $detail->sanPham->hinh_anh)
                                                <img src="{{ asset('images/products/' . $detail->sanPham->hinh_anh) }}" 
                                                     alt="{{ $detail->sanPham->ten_san_pham }}" 
                                                     class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                            @else
                                                <div class="bg-light d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $detail->sanPham->ten_san_pham ?? 'Sản phẩm đã xóa' }}</h6>
                                                @if($detail->sanPham && $detail->sanPham->thuong_hieu)
                                                    <small class="text-muted">{{ $detail->sanPham->thuong_hieu }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($detail->gia) }}đ</td>
                                    <td>{{ $detail->so_luong }}</td>
                                    <td><strong>{{ number_format($detail->gia * $detail->so_luong) }}đ</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th><h5 class="text-success mb-0">{{ number_format($order->tong_tien) }}đ</h5></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ingredients Section (Now part of products) -->
            @php
                $ingredients = $order->chiTietDonHangs->filter(function($item) {
                    return $item->sanPham && $item->sanPham->loai_san_pham === 'ingredient';
                });
            @endphp
            
            @if($ingredients->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-leaf"></i> Nguyên liệu bổ sung
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nguyên liệu</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ingredients as $item)
                                <tr>
                                    <td>{{ $item->sanPham->ten_san_pham ?? 'Nguyên liệu đã xóa' }}</td>
                                    <td>{{ number_format($item->gia_tai_thoi_diem_dat) }}đ</td>
                                    <td>{{ $item->so_luong }}</td>
                                    <td><strong>{{ number_format($item->gia_tai_thoi_diem_dat * $item->so_luong) }}đ</strong></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Customer Info & Actions -->
        <div class="col-md-4">
            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user"></i> Thông tin khách hàng
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->taiKhoan)
                        <div class="customer-info">
                            <p><strong>Họ tên:</strong> {{ $order->taiKhoan->ho_ten }}</p>
                            <p><strong>Email:</strong> {{ $order->taiKhoan->email }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->taiKhoan->so_dien_thoai ?? 'Không có' }}</p>
                            <p><strong>Ngày sinh:</strong> 
                                {{ $order->taiKhoan->ngay_sinh ? $order->taiKhoan->ngay_sinh->format('d/m/Y') : 'Không có' }}
                            </p>
                            <p><strong>Tham gia:</strong> 
                                {{ $order->taiKhoan->created_at ? $order->taiKhoan->created_at->format('d/m/Y') : 'Không có' }}
                            </p>
                        </div>
                        <div class="customer-actions mt-3">
                            <a href="{{ route('admin.users.show', $order->taiKhoan->ma_tai_khoan) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-user"></i> Xem hồ sơ
                            </a>
                        </div>
                    @else
                        <p class="text-muted">Khách hàng vãng lai</p>
                    @endif
                </div>
            </div>

            <!-- Delivery Address -->
            @if($order->diaChi)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt"></i> Địa chỉ giao hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="address-info">
                        <p><strong>Người nhận:</strong> {{ $order->diaChi->ten_nguoi_nhan }}</p>
                        <p><strong>Số điện thoại:</strong> {{ $order->diaChi->so_dien_thoai }}</p>
                        <p><strong>Địa chỉ:</strong></p>
                        <address>
                            {{ $order->diaChi->dia_chi_chi_tiet }}<br>
                            {{ $order->diaChi->phuong_xa }}, {{ $order->diaChi->quan_huyen }}<br>
                            {{ $order->diaChi->tinh_thanh_pho }}
                        </address>
                        @if($order->diaChi->ghi_chu)
                            <p><strong>Ghi chú:</strong> {{ $order->diaChi->ghi_chu }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Order Summary -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-calculator"></i> Tóm tắt đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $subtotal = $order->chiTietDonHangs->sum(function($detail) {
                            return $detail->gia_tai_thoi_diem_dat * $detail->so_luong;
                        });
                    @endphp
                    
                    <div class="order-summary">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($subtotal) }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span>Miễn phí</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-success">{{ number_format($order->tong_tien) }}đ</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStatusForm" method="POST" action="{{ route('admin.orders.update-status', $order->ma_don_hang) }}">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Trạng thái mới</label>
                        <select name="trang_thai" id="newStatus" class="form-select" required>
                            <option value="cho_xu_ly">Chờ xử lý</option>
                            <option value="da_giao">Đã giao</option>
                            <option value="da_huy">Đã hủy</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="ghi_chu" class="form-control" rows="3" 
                                  placeholder="Nhập ghi chú về việc thay đổi trạng thái..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hủy đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.orders.cancel', $order->ma_don_hang) }}">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Bạn có chắc chắn muốn hủy đơn hàng #{{ $order->ma_don_hang }}?
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lý do hủy <span class="text-danger">*</span></label>
                        <textarea name="ly_do_huy" class="form-control" rows="3" 
                                  placeholder="Nhập lý do hủy đơn hàng..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không hủy</button>
                    <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.status-timeline {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.status-item {
    display: flex;
    align-items: center;
    position: relative;
}

.status-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 20px;
    top: 45px;
    width: 2px;
    height: 30px;
    background: #dee2e6;
}

.status-item.completed::after {
    background: #28a745;
}

.status-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #dee2e6;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    z-index: 1;
}

.status-item.completed .status-icon {
    background: #28a745;
    color: white;
}

.status-content h6 {
    margin: 0;
    font-size: 14px;
}

.customer-info p {
    margin-bottom: 8px;
}

.address-info p {
    margin-bottom: 8px;
}

.order-summary {
    font-size: 14px;
}

.card-header {
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/admin-orders.js') }}"></script>
<script>
function updateOrderStatus(status) {
    $('#newStatus').val(status);
    $('#updateStatusModal').modal('show');
}

function cancelOrder() {
    $('#cancelOrderModal').modal('show');
}

function printOrder() {
    window.print();
}
</script>
@endpush