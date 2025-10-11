@extends('admin.layouts.app')

@section('title', 'Chi Tiết Biến Thể Sản Phẩm')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="page-header">
        <div class="row align-items-center">
            <div class="col">
                <h3 class="page-title">
                    <i class="fas fa-eye me-2"></i>
                    Chi Tiết Biến Thể Sản Phẩm
                </h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product-variants.index') }}">Biến Thể Sản Phẩm</a></li>
                        <li class="breadcrumb-item active">Chi Tiết</li>
                    </ol>
                </nav>
            </div>
            <div class="col-auto">
                <div class="btn-group">
                    <a href="{{ route('admin.product-variants.edit', $variant->ma_bien_the) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-1"></i>
                        Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.product-variants.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-8">
            <!-- Variant Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Thông Tin Biến Thể
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Info -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Sản phẩm</label>
                                <div class="detail-value">
                                    <h6 class="mb-1">{{ $variant->sanPham->ten_san_pham }}</h6>
                                    <small class="text-muted">
                                        Danh mục: {{ $variant->sanPham->danhMuc->ten_danh_muc ?? 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Variant Size -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Kích thước</label>
                                <div class="detail-value">
                                    <span class="badge bg-info fs-6">{{ $variant->kich_thuoc }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Price -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Giá</label>
                                <div class="detail-value">
                                    <h5 class="text-success mb-0">{{ number_format($variant->gia, 0, ',', '.') }}đ</h5>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Calories -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Calo</label>
                                <div class="detail-value">
                                    <span class="fs-5">{{ $variant->calo }} <small class="text-muted">cal</small></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stock -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Tồn kho</label>
                                <div class="detail-value">
                                    @php $stockStatus = $variant->stockStatus @endphp
                                    <span class="stock-badge {{ $stockStatus['class'] }}">
                                        <i class="stock-indicator {{ $stockStatus['class'] === 'success' ? 'in-stock' : ($stockStatus['class'] === 'warning' ? 'low-stock' : 'out-of-stock') }}"></i>
                                        {{ $variant->so_luong_ton }} sản phẩm
                                        <small class="d-block">{{ $stockStatus['text'] }}</small>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Trạng thái</label>
                                <div class="detail-value">
                                    <span class="badge bg-{{ $variant->trangThaiClass }} fs-6">
                                        {{ $variant->trangThaiText }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        @if($variant->mo_ta)
                        <div class="col-md-12">
                            <div class="detail-group">
                                <label class="detail-label">Mô tả</label>
                                <div class="detail-value">
                                    <p class="mb-0">{{ $variant->mo_ta }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Dates -->
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Ngày tạo</label>
                                <div class="detail-value">
                                    <small>{{ $variant->ngay_tao ? $variant->ngay_tao->format('d/m/Y H:i:s') : 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="detail-group">
                                <label class="detail-label">Cập nhật cuối</label>
                                <div class="detail-value">
                                    <small>{{ $variant->ngay_cap_nhat ? $variant->ngay_cap_nhat->format('d/m/Y H:i:s') : 'N/A' }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order History -->
            @if($variant->chiTietDonHangs->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Lịch Sử Đơn Hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Mã đơn hàng</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th>Thành tiền</th>
                                    <th>Ngày đặt</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variant->chiTietDonHangs->take(10) as $detail)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $detail->donHang->ma_don_hang) }}" 
                                           class="text-decoration-none">
                                            #{{ $detail->donHang->ma_don_hang }}
                                        </a>
                                    </td>
                                    <td>{{ $detail->so_luong }}</td>
                                    <td>{{ number_format($detail->don_gia, 0, ',', '.') }}đ</td>
                                    <td>{{ number_format($detail->thanh_tien, 0, ',', '.') }}đ</td>
                                    <td>{{ $detail->donHang->ngay_dat_hang ? $detail->donHang->ngay_dat_hang->format('d/m/Y') : 'N/A' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($variant->chiTietDonHangs->count() > 10)
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            Hiển thị 10 đơn hàng gần nhất. Tổng cộng: {{ $variant->chiTietDonHangs->count() }} đơn hàng
                        </small>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-xl-4">
            <!-- Variant Image -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-image me-1"></i>
                        Hình ảnh biến thể
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($variant->hinh_anh)
                        <img src="{{ asset('images/variants/' . $variant->hinh_anh) }}" 
                             alt="{{ $variant->sanPham->ten_san_pham }} - {{ $variant->kich_thuoc }}" 
                             class="img-fluid rounded">
                    @else
                        <div class="no-image-placeholder">
                            <i class="fas fa-image fa-3x text-muted"></i>
                            <p class="text-muted mt-2">Chưa có hình ảnh</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Product Image -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-box me-1"></i>
                        Sản phẩm gốc
                    </h6>
                </div>
                <div class="card-body text-center">
                    @if($variant->sanPham->hinh_anh)
                        <img src="{{ asset('images/products/' . $variant->sanPham->hinh_anh) }}" 
                             alt="{{ $variant->sanPham->ten_san_pham }}" 
                             class="img-fluid rounded mb-2">
                    @endif
                    <h6>{{ $variant->sanPham->ten_san_pham }}</h6>
                    <p class="text-muted small">{{ $variant->sanPham->danhMuc->ten_danh_muc ?? 'N/A' }}</p>
                    <a href="{{ route('admin.products.show', $variant->sanPham->ma_san_pham) }}" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>
                        Xem sản phẩm
                    </a>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-1"></i>
                        Thống kê
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-label">Tổng đơn hàng</div>
                        <div class="stat-value">{{ $variant->chiTietDonHangs->count() }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">Tổng số lượng bán</div>
                        <div class="stat-value">{{ $variant->chiTietDonHangs->sum('so_luong') }}</div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">Tổng doanh thu</div>
                        <div class="stat-value text-success">
                            {{ number_format($variant->chiTietDonHangs->sum('thanh_tien'), 0, ',', '.') }}đ
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-label">Có trong giỏ hàng</div>
                        <div class="stat-value">{{ $variant->chiTietGioHangs->count() }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-bolt me-1"></i>
                        Thao tác nhanh
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.product-variants.edit', $variant->ma_bien_the) }}" 
                           class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Chỉnh sửa biến thể
                        </a>
                        
                        <button type="button" class="btn btn-{{ $variant->trang_thai ? 'warning' : 'success' }} btn-sm toggle-status"
                                data-id="{{ $variant->ma_bien_the }}">
                            <i class="fas fa-{{ $variant->trang_thai ? 'times' : 'check' }} me-1"></i>
                            {{ $variant->trang_thai ? 'Vô hiệu hóa' : 'Kích hoạt' }}
                        </button>
                        
                        <a href="{{ route('admin.product-variants.create', ['product_id' => $variant->ma_san_pham]) }}" 
                           class="btn btn-info btn-sm">
                            <i class="fas fa-plus me-1"></i>
                            Thêm biến thể mới
                        </a>
                        
                        <button type="button" class="btn btn-danger btn-sm delete-variant"
                                data-id="{{ $variant->ma_bien_the }}"
                                data-name="{{ $variant->sanPham->ten_san_pham }} - {{ $variant->kich_thuoc }}">
                            <i class="fas fa-trash me-1"></i>
                            Xóa biến thể
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa biến thể sản phẩm <strong id="variant-name"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Hành động này không thể hoàn tác!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Xóa</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.detail-group {
    margin-bottom: 1.5rem;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: block;
}

.detail-value {
    color: #212529;
}

.no-image-placeholder {
    padding: 3rem 1rem;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    background: #f8f9fa;
}

.stat-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-label {
    font-size: 0.875rem;
    color: #6c757d;
}

.stat-value {
    font-weight: 600;
    color: #495057;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle status
    $('.toggle-status').click(function() {
        const variantId = $(this).data('id');
        const button = $(this);
        
        $.ajax({
            url: `/admin/product-variants/${variantId}/toggle-status`,
            method: 'PATCH',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                }
            },
            error: function() {
                showToast('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
            }
        });
    });
    
    // Delete variant
    $('.delete-variant').click(function() {
        const variantId = $(this).data('id');
        const variantName = $(this).data('name');
        
        $('#variant-name').text(variantName);
        $('#confirm-delete').data('id', variantId);
        $('#deleteModal').modal('show');
    });
    
    $('#confirm-delete').click(function() {
        const variantId = $(this).data('id');
        
        $.ajax({
            url: `/admin/product-variants/${variantId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = '{{ route("admin.product-variants.index") }}';
                } else {
                    showToast('error', response.message);
                }
            },
            error: function() {
                showToast('error', 'Có lỗi xảy ra khi xóa biến thể');
            },
            complete: function() {
                $('#deleteModal').modal('hide');
            }
        });
    });
});

function showToast(type, message) {
    // Implementation depends on your notification system
    console.log(`${type}: ${message}`);
}
</script>
@endpush