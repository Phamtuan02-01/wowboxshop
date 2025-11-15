@extends('admin.layouts.app')

@section('title', 'Chi tiết Sản phẩm')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-eye"></i> Chi tiết Sản phẩm</h1>
        <div class="page-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
            <a href="{{ route('admin.products.edit', $product->ma_san_pham) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Chỉnh sửa
            </a>
            <form method="POST" action="{{ route('admin.products.toggle-status', $product->ma_san_pham) }}" 
                  style="display: inline;">
                @csrf
                @method('PATCH')
                <button type="submit" 
                        class="btn {{ $product->trang_thai ? 'btn-warning' : 'btn-success' }}">
                    <i class="fas {{ $product->trang_thai ? 'fa-pause' : 'fa-play' }}"></i>
                    {{ $product->trang_thai ? 'Tạm ngưng' : 'Kích hoạt' }}
                </button>
            </form>
        </div>
    </div>

    <div class="row">
        <!-- Product Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Thông tin chi tiết</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->hinh_anh)
                                <div class="product-image-container text-center mb-4">
                                    <img src="{{ asset('images/products/' . $product->hinh_anh) }}" 
                                         alt="{{ $product->ten_san_pham }}" 
                                         class="product-main-image">
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                    <p class="text-muted mt-2">Chưa có hình ảnh</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless product-details">
                                <tr>
                                    <td><strong>Mã sản phẩm:</strong></td>
                                    <td>{{ $product->ma_san_pham }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tên sản phẩm:</strong></td>
                                    <td>{{ $product->ten_san_pham }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Danh mục:</strong></td>
                                    <td>
                                        <span class="badge badge-primary" style="color: black;">
                                            {{ $product->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Giá bán:</strong></td>
                                    <td>
                                        <span class="text-primary fw-bold">{{ number_format($product->gia, 0, ',', '.') }} VNĐ</span>
                                        @if($product->gia_khuyen_mai)
                                            <br><span class="text-success">Giá KM: {{ number_format($product->gia_khuyen_mai, 0, ',', '.') }} VNĐ</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tồn kho:</strong></td>
                                    <td>
                                        <span class="stock-badge {{ $product->so_luong_ton_kho <= 0 ? 'out-of-stock' : ($product->so_luong_ton_kho <= 10 ? 'low-stock' : 'in-stock') }}">
                                            {{ $product->so_luong_ton_kho }} sản phẩm
                                        </span>
                                    </td>
                                </tr>
                                @if($product->thuong_hieu)
                                <tr>
                                    <td><strong>Thương hiệu:</strong></td>
                                    <td>{{ $product->thuong_hieu }}</td>
                                </tr>
                                @endif
                                @if($product->xuat_xu)
                                <tr>
                                    <td><strong>Xuất xứ:</strong></td>
                                    <td>{{ $product->xuat_xu }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Trạng thái:</strong></td>
                                    <td>
                                        <span class="badge {{ $product->trang_thai ? 'badge-success' : 'badge-danger' }}">
                                            {{ $product->trang_thai ? 'Hoạt động' : 'Tạm ngưng' }}
                                        </span>
                                        @if($product->la_noi_bat)
                                            <span class="badge badge-warning">Nổi bật</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($product->mo_ta)
                    <div class="product-description mt-4">
                        <h6><i class="fas fa-align-left"></i> Mô tả sản phẩm</h6>
                        <div class="description-content">
                            {!! nl2br(e($product->mo_ta)) !!}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics and Actions -->
        <div class="col-md-4">
            <!-- Product Stats -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-bar"></i> Thống kê</h5>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-info">
                            <h4>{{ number_format($product->getTotalSoldQuantity()) }}</h4>
                            <p>Đã bán</p>
                        </div>
                    </div>

                    <div class="stat-item">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <h4>{{ number_format($product->diem_danh_gia_trung_binh ?? 0, 1) }}</h4>
                            <p>Đánh giá TB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-bolt"></i> Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.products.edit', $product->ma_san_pham) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Chỉnh sửa thông tin
                        </a>
                        
                        <form method="POST" action="{{ route('admin.products.toggle-status', $product->ma_san_pham) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" 
                                    class="btn {{ $product->trang_thai ? 'btn-warning' : 'btn-success' }} w-100">
                                <i class="fas {{ $product->trang_thai ? 'fa-pause' : 'fa-play' }}"></i>
                                {{ $product->trang_thai ? 'Tạm ngưng bán' : 'Kích hoạt bán' }}
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Xóa sản phẩm
                        </button>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info"></i> Thông tin hệ thống</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Ngày tạo:</strong></td>
                            <td>{{ $product->ngay_tao->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật gần nhất:</strong></td>
                            <td>{{ $product->ngay_cap_nhat->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Người tạo:</strong></td>
                            <td>Admin</td>
                        </tr>
                    </table>
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
                <h5 class="modal-title">Xác nhận xóa sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa sản phẩm "<strong>{{ $product->ten_san_pham }}</strong>"?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    Hành động này không thể hoàn tác!
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('admin.products.destroy', $product->ma_san_pham) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa sản phẩm</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.product-main-image {
    max-width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
}

.no-image-placeholder {
    height: 400px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.product-details td {
    padding: 8px 0;
    vertical-align: top;
}

.product-details td:first-child {
    width: 35%;
}

.price-original {
    font-size: 1.2em;
    font-weight: 600;
    color: #28a745;
}

.price-sale {
    font-size: 1.1em;
    font-weight: 600;
    color: #dc3545;
}

.stock-badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
}

.stock-badge.in-stock {
    background: #d4edda;
    color: #155724;
}

.stock-badge.low-stock {
    background: #fff3cd;
    color: #856404;
}

.stock-badge.out-of-stock {
    background: #f8d7da;
    color: #721c24;
}

.description-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 10px;
    line-height: 1.6;
}

.stat-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.stat-item:last-child {
    margin-bottom: 0;
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 20px;
    margin-right: 15px;
}

.stat-info h4 {
    margin: 0;
    font-size: 1.5em;
    font-weight: 700;
    color: #2c3e50;
}

.stat-info p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
}
</style>

<script>
function confirmDelete() {
    $('#deleteModal').modal('show');
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
});
</script>
@endsection