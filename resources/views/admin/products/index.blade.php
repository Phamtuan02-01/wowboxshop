@extends('admin.layouts.app')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="admin-content">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['total_products']) }}</h3>
                    <p>Tổng sản phẩm</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['active_products']) }}</h3>
                    <p>Đang hoạt động</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-pause-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['inactive_products']) }}</h3>
                    <p>Tạm ngưng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['out_of_stock']) }}</h3>
                    <p>Hết hàng</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-box"></i> Quản lý Sản phẩm</h1>
        <div class="page-actions">
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Thêm sản phẩm mới
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.products.index') }}" class="filter-form">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="category" class="form-control">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->ma_danh_muc }}" 
                                        {{ request('category') == $category->ma_danh_muc ? 'selected' : '' }}>
                                    {{ $category->ten_danh_muc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="status" class="form-control">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="sort_by" class="form-control">
                            <option value="ngay_tao" {{ request('sort_by') == 'ngay_tao' ? 'selected' : '' }}>Ngày tạo</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên sản phẩm</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá</option>
                            <option value="stock" {{ request('sort_by') == 'stock' ? 'selected' : '' }}>Tồn kho</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <select name="sort_order" class="form-control">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 60px;">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th style="width: 100px;">Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Trạng thái</th>
                    <th style="width: 200px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <input type="checkbox" class="product-checkbox" value="{{ $product->ma_san_pham }}">
                    </td>
                    <td>
                        @if($product->hinh_anh)
                            <img src="{{ asset('images/products/' . $product->hinh_anh) }}" 
                                 alt="{{ $product->ten_san_pham }}" 
                                 class="product-image">
                        @else
                            <div class="no-image">
                                <i class="fas fa-image"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="product-info">
                            <h5>{{ $product->ten_san_pham }}</h5>
                            @if($product->thuong_hieu)
                                <small class="text-muted">{{ $product->thuong_hieu }}</small>
                            @endif
                            @if($product->la_noi_bat)
                                <span class="badge badge-warning">Nổi bật</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $product->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}</td>
                    <td>
                        <div class="price-info">
                            <strong>{{ number_format($product->gia) }}đ</strong>
                            @if($product->gia_khuyen_mai)
                                <br><small class="text-success">KM: {{ number_format($product->gia_khuyen_mai) }}đ</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="stock-badge {{ $product->so_luong_ton_kho <= 0 ? 'out-of-stock' : ($product->so_luong_ton_kho <= 10 ? 'low-stock' : 'in-stock') }}">
                            {{ $product->so_luong_ton_kho }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $product->trang_thai ? 'badge-success' : 'badge-danger' }} me-2">
                                {{ $product->trang_thai ? 'Hoạt động' : 'Tạm ngưng' }}
                            </span>
                            <form method="POST" action="{{ route('admin.products.toggle-status', $product->ma_san_pham) }}" 
                                  style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="btn btn-sm {{ $product->trang_thai ? 'btn-warning' : 'btn-success' }}" 
                                        title="{{ $product->trang_thai ? 'Tạm ngưng' : 'Kích hoạt' }}">
                                    <i class="fas {{ $product->trang_thai ? 'fa-pause' : 'fa-play' }}"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="{{ route('admin.products.show', $product->ma_san_pham) }}" 
                               class="btn btn-sm btn-info" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->ma_san_pham) }}" 
                               class="btn btn-sm btn-primary" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product->ma_san_pham) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không có sản phẩm nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="pagination-wrapper">
            {{ $products->appends(request()->query())->links('custom.pagination') }}
        </div>
    @endif
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thao tác hàng loạt</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="bulkActionForm" method="POST" action="{{ route('admin.products.bulk-action') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Chọn thao tác:</label>
                        <select name="action" class="form-control" required>
                            <option value="">-- Chọn thao tác --</option>
                            <option value="activate">Kích hoạt</option>
                            <option value="deactivate">Tạm ngưng</option>
                            <option value="delete">Xóa</option>
                        </select>
                    </div>
                    <input type="hidden" name="product_ids" id="selectedProducts">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thực hiện</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
}

.no-image {
    width: 60px;
    height: 60px;
    background: #f8f9fa;
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.product-info h5 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
}

.stock-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
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

.price-info strong {
    color: #28a745;
}

.bulk-actions {
    margin-bottom: 20px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    display: none;
}

.bulk-actions.show {
    display: block;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const productCheckboxes = document.querySelectorAll('.product-checkbox');
    const bulkActionModal = document.getElementById('bulkActionModal');
    
    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        productCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });
    
    // Individual checkbox functionality
    productCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });
    
    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const bulkActions = document.querySelector('.bulk-actions');
        
        if (checkedBoxes.length > 0) {
            if (!bulkActions) {
                createBulkActionsBar();
            } else {
                bulkActions.classList.add('show');
            }
            updateSelectedCount(checkedBoxes.length);
        } else {
            if (bulkActions) {
                bulkActions.classList.remove('show');
            }
        }
    }
    
    function createBulkActionsBar() {
        const bulkActionsHtml = `
            <div class="bulk-actions show">
                <div class="d-flex justify-content-between align-items-center">
                    <span id="selected-count">0 sản phẩm được chọn</span>
                    <button type="button" class="btn btn-primary" onclick="openBulkActionModal()">
                        <i class="fas fa-cogs"></i> Thao tác hàng loạt
                    </button>
                </div>
            </div>
        `;
        
        document.querySelector('.filters-section').insertAdjacentHTML('afterend', bulkActionsHtml);
    }
    
    function updateSelectedCount(count) {
        const selectedCountElement = document.getElementById('selected-count');
        if (selectedCountElement) {
            selectedCountElement.textContent = `${count} sản phẩm được chọn`;
        }
    }
    
    window.openBulkActionModal = function() {
        const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
        const selectedIds = Array.from(checkedBoxes).map(checkbox => checkbox.value);
        
        document.getElementById('selectedProducts').value = selectedIds.join(',');
        $('#bulkActionModal').modal('show');
    };
});
</script>
@endsection