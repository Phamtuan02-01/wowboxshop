@extends('admin.layouts.app')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="admin-content">
    <!-- Stats Cards -->
    <div class="row">
        <div class="col-md-2">
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
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['total_regular_products']) }}</h3>
                    <p>Sản phẩm</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-leaf"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['total_ingredients']) }}</h3>
                    <p>Nguyên liệu</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['active_products']) }}</h3>
                    <p>hoạt động</p>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($stats['featured_products']) }}</h3>
                    <p>Nổi bật</p>
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
                <div class="col-search">
                    <div class="form-group">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Danh mục</label>
                        <select name="category" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->ma_danh_muc }}" 
                                        {{ request('category') == $category->ma_danh_muc ? 'selected' : '' }}>
                                    {{ $category->ten_danh_muc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-select-small">
                    <div class="form-group">
                        <label class="form-label">Loại</label>
                        <select name="type" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="product" {{ request('type') == 'product' ? 'selected' : '' }}>SP</option>
                            <option value="ingredient" {{ request('type') == 'ingredient' ? 'selected' : '' }}>NL</option>
                        </select>
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                        </select>
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Sắp xếp</label>
                        <select name="sort_by" class="form-select">
                            <option value="ngay_tao" {{ request('sort_by') == 'ngay_tao' ? 'selected' : '' }}>Ngày tạo</option>
                            <option value="name" {{ request('sort_by') == 'name' ? 'selected' : '' }}>Tên</option>
                            <option value="price" {{ request('sort_by') == 'price' ? 'selected' : '' }}>Giá</option>
                            <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Đánh giá</option>
                            <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Xem</option>
                        </select>
                    </div>
                </div>
                <div class="col-select-small">
                    <div class="form-group">
                        <label class="form-label">Thứ tự</label>
                        <select name="sort_order" class="form-select">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Giảm</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Tăng</option>
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
                        <a href="{{ route('admin.products.index') }}" class="btn btn-light" title="Đặt lại bộ lọc">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
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
                    <th>Thông tin sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Loại</th>
                    <th>Giá</th>
                    <th>Đánh giá</th>
                    <th>Trạng thái</th>
                    <th width="150">Thao tác</th>
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
                                <small class="text-muted d-block">
                                    <i class="fas fa-tag"></i> {{ $product->thuong_hieu }}
                                </small>
                            @endif
                            @if($product->xuat_xu)
                                <small class="text-muted d-block">
                                    <i class="fas fa-map-marker-alt"></i> {{ $product->xuat_xu }}
                                </small>
                            @endif
                            <div class="badges mt-1">
                                @if($product->la_noi_bat)
                                    <span class="badge badge-warning">
                                        <i class="fas fa-star"></i> Nổi bật
                                    </span>
                                @endif
                                @if($product->luot_xem > 100)
                                    <span class="badge badge-info">
                                        <i class="fas fa-eye"></i> Hot
                                    </span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="category-badge">
                            {{ $product->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}
                        </span>
                    </td>
                    <td>
                        <span class="product-type-badge {{ $product->loai_san_pham }}">
                            @if($product->loai_san_pham === 'ingredient')
                                <i class="fas fa-leaf"></i> Nguyên liệu
                                @if($product->calo)
                                    <small class="d-block">{{ $product->calo }} kcal</small>
                                @endif
                            @else
                                <i class="fas fa-shopping-bag"></i> Sản phẩm
                            @endif
                        </span>
                    </td>
                    <td>
                        <div class="price-info">
                            @if($product->gia_khuyen_mai)
                                <div class="original-price">{{ number_format($product->gia) }}đ</div>
                                <div class="sale-price">{{ number_format($product->gia_khuyen_mai) }}đ</div>
                                <div class="discount-percent">
                                    -{{ round((($product->gia - $product->gia_khuyen_mai) / $product->gia) * 100) }}%
                                </div>
                            @else
                                <div class="current-price">{{ number_format($product->gia) }}đ</div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="rating-info">
                            @if($product->so_luot_danh_gia > 0)
                                <div class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= $product->diem_danh_gia_trung_binh ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">
                                    {{ number_format($product->diem_danh_gia_trung_binh, 1) }}/5 
                                    ({{ $product->so_luot_danh_gia }} đánh giá)
                                </small>
                            @else
                                <small class="text-muted">Chưa có đánh giá</small>
                            @endif
                            @if($product->luot_xem > 0)
                                <div class="views-count">
                                    <i class="fas fa-eye"></i> {{ number_format($product->luot_xem) }}
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge {{ $product->trang_thai ? 'bg-success' : 'bg-secondary' }} text-white me-2" style="padding: 0.35em 0.65em;">
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
                               class="btn btn-outline-info btn-action" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->ma_san_pham) }}" 
                               class="btn btn-outline-warning btn-action" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if($product->bienThes->count() > 0)
                                <a href="{{ route('admin.product-variants.index', ['product' => $product->ma_san_pham]) }}" 
                                   class="btn btn-outline-secondary btn-action" title="Quản lý biến thể">
                                    <i class="fas fa-list"></i>
                                </a>
                            @endif
                            <form method="POST" action="{{ route('admin.products.destroy', $product->ma_san_pham) }}" 
                                  style="display: inline;" 
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-action" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không có sản phẩm nào.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sản phẩm đầu tiên
                        </a>
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

.product-info .badges .badge {
    margin-right: 4px;
    font-size: 10px;
}

.category-badge {
    background: #e9ecef;
    color: #495057;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 500;
}

.product-type-badge {
    padding: 6px 10px;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 600;
    display: inline-block;
}

.product-type-badge.product {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.product-type-badge.ingredient {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    color: white;
}

.price-info {
    text-align: right;
}

.price-info .original-price {
    font-size: 12px;
    text-decoration: line-through;
    color: #6c757d;
}

.price-info .sale-price {
    font-size: 14px;
    font-weight: 600;
    color: #dc3545;
}

.price-info .current-price {
    font-size: 14px;
    font-weight: 600;
    color: #28a745;
}

.price-info .discount-percent {
    font-size: 10px;
    background: #dc3545;
    color: white;
    padding: 2px 4px;
    border-radius: 8px;
    display: inline-block;
    margin-top: 2px;
}

.rating-info {
    text-align: center;
    font-size: 12px;
}

.rating-stars {
    margin-bottom: 4px;
}

.rating-stars i {
    font-size: 12px;
}

.views-count {
    margin-top: 4px;
    color: #6c757d;
    font-size: 11px;
}

/* Action buttons styles now in admin.css */

.bulk-actions {
    margin-bottom: 20px;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 8px;
    border-left: 4px solid #007bff;
    display: none;
}

.bulk-actions.show {
    display: block;
}

.stat-card {
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.filters-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 1px solid #dee2e6;
}

.table-container {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.table th {
    background: #f8f9fa;
    border: none;
    font-weight: 600;
    color: #495057;
    font-size: 13px;
    padding: 15px 10px;
}

.table td {
    border: none;
    border-bottom: 1px solid #f1f3f4;
    padding: 15px 10px;
    vertical-align: middle;
}

.table tbody tr:hover {
    background: #f8f9fa;
}

@media (max-width: 768px) {
    /* Action buttons responsive styles now in admin.css */
    
    .filters-section .row > div {
        margin-bottom: 10px;
    }
    
    .stat-card {
        margin-bottom: 15px;
    }
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