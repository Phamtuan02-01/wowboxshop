@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('breadcrumb')
    <li class="breadcrumb-item active">Đơn hàng</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Quản lý Đơn hàng</h1>
                <p class="text-muted">Theo dõi và xử lý các đơn hàng của khách hàng</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-outline-success" onclick="exportOrders()">
                    <i class="fas fa-download"></i> Xuất Excel
                </button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($stats['total']) }}</h3>
                <p>Đơn hàng</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($stats['pending']) }}</h3>
                <p>Chờ xác nhận</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($stats['completed']) }}</h3>
                <p>Đã giao</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($stats['cancelled']) }}</h3>
                <p>Đã hủy</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-info">
                <h3>{{ number_format($stats['total_revenue']) }}đ</h3>
                <p>Doanh thu</p>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="filter-form">
            <div class="row">
                <div class="col-search">
                    <div class="form-group">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" 
                               placeholder="Mã đơn hàng, tên khách hàng..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="cho_xac_nhan" {{ request('status') === 'cho_xac_nhan' ? 'selected' : '' }}>Chờ xác nhận</option>
                            <option value="da_giao" {{ request('status') === 'da_giao' ? 'selected' : '' }}>Đã giao</option>
                            <option value="da_huy" {{ request('status') === 'da_huy' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" 
                               value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" 
                               value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Sắp xếp</label>
                        <select name="sort_by" class="form-select">
                            <option value="ngay_dat_hang" {{ request('sort_by') === 'ngay_dat_hang' ? 'selected' : '' }}>Ngày đặt</option>
                            <option value="tong_tien" {{ request('sort_by') === 'tong_tien' ? 'selected' : '' }}>Tổng tiền</option>
                            <option value="trang_thai" {{ request('sort_by') === 'trang_thai' ? 'selected' : '' }}>Trạng thái</option>
                        </select>
                    </div>
                </div>
                <div class="col-button">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary" title="Tìm kiếm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-button">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-light" title="Đặt lại bộ lọc">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="table-container">
        <div class="table-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Danh sách đơn hàng ({{ $orders->total() }} kết quả)
                </h6>
                <div class="bulk-actions" style="display: none;">
                    <select id="bulk-action" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Chọn thao tác</option>
                        <option value="export">Xuất đã chọn</option>
                        <option value="update-status">Cập nhật trạng thái</option>
                    </select>
                    <button type="button" class="btn btn-sm btn-outline-primary ms-2" onclick="executeBulkAction()">
                        Thực hiện
                    </button>
                </div>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="select-all" class="form-check-input">
                        </th>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" 
                                   value="{{ $order->ma_don_hang }}">
                        </td>
                        <td>
                            <strong>#{{ $order->ma_don_hang }}</strong>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->taiKhoan->ho_ten ?? 'Khách vãng lai' }}</strong>
                                @if($order->taiKhoan)
                                    <br><small class="text-muted">{{ $order->taiKhoan->email }}</small>
                                    <br><small class="text-muted">{{ $order->taiKhoan->so_dien_thoai }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="order-products">
                                @foreach($order->chiTietDonHangs->take(2) as $detail)
                                    <div class="d-flex align-items-center mb-1">
                                        @if($detail->sanPham && $detail->sanPham->hinh_anh)
                                            <img src="{{ asset('images/products/' . $detail->sanPham->hinh_anh) }}" 
                                                 alt="{{ $detail->sanPham->ten_san_pham }}" 
                                                 class="img-thumbnail me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                        @endif
                                        <small>
                                            {{ $detail->sanPham->ten_san_pham ?? 'Sản phẩm đã xóa' }} 
                                            <span class="text-muted">(x{{ $detail->so_luong }})</span>
                                        </small>
                                    </div>
                                @endforeach
                                @if($order->chiTietDonHangs->count() > 2)
                                    <small class="text-muted">
                                        + {{ $order->chiTietDonHangs->count() - 2 }} sản phẩm khác
                                    </small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <strong class="text-success">{{ number_format($order->tong_tien) }}đ</strong>
                        </td>
                        <td>
                            <span class="badge bg-{{ $order->getStatusBadgeClass() }}">
                                {{ $order->trang_thai_text }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $order->ngay_cap_nhat ? $order->ngay_cap_nhat->diffForHumans() : 'Chưa cập nhật' }}
                            </small>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $order->ngay_dat_hang->format('d/m/Y') }}</strong>
                                <br><small class="text-muted">{{ $order->ngay_dat_hang->format('H:i') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.orders.show', $order->ma_don_hang) }}" 
                                   class="btn btn-outline-info btn-action" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(!in_array($order->trang_thai, ['da_giao', 'Đã giao', 'da_huy', 'Đã hủy']))
                                <div class="dropdown" style="display: inline;">
                                    <button class="btn btn-outline-warning btn-action dropdown-toggle" 
                                            type="button" data-bs-toggle="dropdown" title="Cập nhật">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->ma_don_hang }}, 'da_giao')">
                                            <i class="fas fa-check-circle text-success"></i> Đã giao
                                        </a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#" onclick="cancelOrder({{ $order->ma_don_hang }})">
                                            <i class="fas fa-times-circle"></i> Hủy đơn
                                        </a></li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Không có đơn hàng nào</h5>
                            <p class="text-muted">Chưa có đơn hàng nào trong hệ thống.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($orders->hasPages())
        <div class="table-footer">
            {{ $orders->appends(request()->query())->links('custom.pagination') }}
        </div>
        @endif
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
            <form id="updateStatusForm" method="POST">
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
            <form id="cancelOrderForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Bạn có chắc chắn muốn hủy đơn hàng này?
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

@push('scripts')
<script src="{{ asset('js/admin-orders.js') }}"></script>
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#select-all').change(function() {
        $('.item-checkbox').prop('checked', this.checked);
        toggleBulkActions();
    });
    
    // Individual checkbox
    $('.item-checkbox').change(function() {
        toggleBulkActions();
        
        const totalCheckboxes = $('.item-checkbox').length;
        const checkedCheckboxes = $('.item-checkbox:checked').length;
        $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
});

function toggleBulkActions() {
    const checkedItems = $('.item-checkbox:checked').length;
    if (checkedItems > 0) {
        $('.bulk-actions').show();
    } else {
        $('.bulk-actions').hide();
    }
}

function updateOrderStatus(orderId, status) {
    const baseUrl = '{{ url("/admin/orders") }}';
    $('#updateStatusForm').attr('action', `${baseUrl}/${orderId}/status`);
    $('#newStatus').val(status);
    $('#updateStatusModal').modal('show');
}

function cancelOrder(orderId) {
    const baseUrl = '{{ url("/admin/orders") }}';
    $('#cancelOrderForm').attr('action', `${baseUrl}/${orderId}/cancel`);
    $('#cancelOrderModal').modal('show');
}

function exportOrders() {
    const form = document.createElement('form');
    form.method = 'GET';
    form.action = '{{ route("admin.orders.export") }}';
    
    // Add current filter parameters
    const params = new URLSearchParams(window.location.search);
    params.forEach((value, key) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}

function executeBulkAction() {
    const action = $('#bulk-action').val();
    const selectedItems = $('.item-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    if (!action) {
        alert('Vui lòng chọn thao tác!');
        return;
    }
    
    if (selectedItems.length === 0) {
        alert('Vui lòng chọn ít nhất một đơn hàng!');
        return;
    }
    
    // Handle bulk actions
    switch (action) {
        case 'export':
            exportSelectedOrders(selectedItems);
            break;
        case 'update-status':
            // Show bulk update status modal
            break;
    }
}

function exportSelectedOrders(orderIds) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.orders.export") }}';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);
    
    const idsInput = document.createElement('input');
    idsInput.type = 'hidden';
    idsInput.name = 'order_ids';
    idsInput.value = orderIds.join(',');
    form.appendChild(idsInput);
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush