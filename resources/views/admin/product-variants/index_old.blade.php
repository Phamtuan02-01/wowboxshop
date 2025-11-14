@extends('layouts.admin')

@section('title', 'Quản lý biến thể sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item active">Biến thể sản phẩm</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Quản lý biến thể sản phẩm</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Biến thể sản phẩm</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.product-variants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm biến thể
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $variants->total() }}</div>
                    <div class="stat-label">Tổng biến thể</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $variants->where('trang_thai', true)->count() }}</div>
                    <div class="stat-label">Đang hoạt động</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $variants->where('so_luong_ton', '>', 0)->count() }}</div>
                    <div class="stat-label">Còn hàng</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $variants->where('so_luong_ton', '<=', 0)->count() }}</div>
                    <div class="stat-label">Hết hàng</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filter-row">
        <form method="GET" action="{{ route('admin.product-variants.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Tìm kiếm theo tên hoặc kích thước</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nhập tên sản phẩm hoặc kích thước...">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="category_filter">Danh mục</label>
                        <select class="form-control" id="category_filter" name="category">
                            <option value="">Tất cả danh mục</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->ma_danh_muc }}" {{ request('category') == $category->ma_danh_muc ? 'selected' : '' }}>
                                    {{ $category->ten_danh_muc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="status_filter">Trạng thái</label>
                        <select class="form-control" id="status_filter" name="status">
                            <option value="">Tất cả</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.product-variants.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Bulk Actions -->
    <div id="bulk-actions-bar" class="bulk-actions" style="display: none;">
        <div class="d-flex align-items-center">
            <span class="me-3">
                <i class="fas fa-check-square text-primary"></i>
                Đã chọn <strong id="selected-count">0</strong> biến thể
            </span>
            <select id="bulk-action" class="form-select me-2" style="width: auto;">
                <option value="">Chọn hành động</option>
                <option value="activate">Kích hoạt</option>
                <option value="deactivate">Vô hiệu hóa</option>
                <option value="delete">Xóa</option>
            </select>
            <button type="button" class="btn btn-sm btn-primary me-2" onclick="performBulkAction()">
                <i class="fas fa-play"></i> Thực hiện
            </button>
            <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">
                <i class="fas fa-times"></i> Hủy chọn
            </button>
        </div>
    </div>

    <!-- Product Variants Table -->
    <div class="table-container">
        <div class="table-header">
            <h6 class="section-title mb-0">Danh sách biến thể sản phẩm</h6>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportData()">
                        <i class="fas fa-download me-2"></i>Xuất Excel
                    </a></li>
                </ul>
            </div>
        </div>
            <h6 class="mb-0">
                <i class="fas fa-filter me-2"></i>
                Bộ lọc và tìm kiếm
            </h6>
        </div>
        <div class="variant-filter-body">
            <form method="GET" action="{{ route('admin.product-variants.index') }}" class="row g-3">
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control filter-control" 
                               placeholder="Tên sản phẩm, kích thước..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="filter-group">
                        <label class="filter-label">Sản phẩm</label>
                        <select name="product_id" class="form-select filter-control">
                            <option value="">Tất cả sản phẩm</option>
                            @foreach($products as $product)
                                <option value="{{ $product->ma_san_pham }}" 
                                    {{ request('product_id') == $product->ma_san_pham ? 'selected' : '' }}>
                                    {{ $product->ten_san_pham }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="filter-group">
                        <label class="filter-label">Trạng thái</label>
                        <select name="status" class="form-select filter-control">
                            <option value="">Tất cả</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="filter-group">
                        <label class="filter-label">Tồn kho</label>
                        <select name="stock_status" class="form-select filter-control">
                            <option value="">Tất cả</option>
                            <option value="in_stock" {{ request('stock_status') === 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="low_stock" {{ request('stock_status') === 'low_stock' ? 'selected' : '' }}>Sắp hết</option>
                            <option value="out_of_stock" {{ request('stock_status') === 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="filter-group">
                        <label class="filter-label">Sắp xếp</label>
                        <select name="sort_by" class="form-select filter-control">
                            <option value="ngay_tao" {{ request('sort_by') === 'ngay_tao' ? 'selected' : '' }}>Ngày tạo</option>
                            <option value="product_name" {{ request('sort_by') === 'product_name' ? 'selected' : '' }}>Tên sản phẩm</option>
                            <option value="gia" {{ request('sort_by') === 'gia' ? 'selected' : '' }}>Giá</option>
                            <option value="so_luong_ton" {{ request('sort_by') === 'so_luong_ton' ? 'selected' : '' }}>Tồn kho</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>
                        Tìm kiếm
                    </button>
                    <a href="{{ route('admin.product-variants.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-undo me-2"></i>
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Bulk Actions -->
    <div id="bulk-actions-section" class="variant-bulk-section">
        <div class="bulk-actions-content">
            <div class="bulk-selection-info">
                <div class="selection-count">
                    <i class="fas fa-check-circle me-2"></i>
                    <span id="selected-count">0</span> biến thể đã chọn
                </div>
            </div>
            <div class="bulk-action-buttons">
                <button type="button" class="bulk-btn" onclick="bulkAction('activate')">
                    <i class="fas fa-check me-1"></i> Kích hoạt
                </button>
                <button type="button" class="bulk-btn" onclick="bulkAction('deactivate')">
                    <i class="fas fa-times me-1"></i> Vô hiệu hóa
                </button>
                <button type="button" class="bulk-btn" onclick="bulkAction('delete')">
                    <i class="fas fa-trash me-1"></i> Xóa
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Product Variants Table -->
    <div class="variant-table-wrapper">
        <div class="variant-table-header">
            <h6 class="mb-0">
                <i class="fas fa-table me-2"></i>
                Danh sách biến thể sản phẩm
            </h6>
        </div>
        <table class="table variant-table">
            <thead>
                <tr>
                    <th width="40">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="select-all">
                            <label class="custom-control-label" for="select-all"></label>
                        </div>
                    </th>
                    <th width="100">Hình ảnh</th>
                    <th>Thông tin biến thể</th>
                    <th width="140">Giá</th>
                    <th width="140">Tồn kho</th>
                    <th width="120">Trạng thái</th>
                    <th width="140">Ngày tạo</th>
                    <th width="140">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($variants as $variant)
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input variant-checkbox" 
                                   id="variant-{{ $variant->ma_bien_the }}" 
                                   value="{{ $variant->ma_bien_the }}">
                            <label class="custom-control-label" for="variant-{{ $variant->ma_bien_the }}"></label>
                        </div>
                    </td>
                    <td>
                        <div class="variant-image-container">
                            @if($variant->hinh_anh)
                                <img src="{{ asset('images/variants/' . $variant->hinh_anh) }}" 
                                     alt="{{ $variant->sanPham->ten_san_pham }}" 
                                     class="variant-thumbnail">
                            @else
                                <div class="variant-placeholder">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="variant-info-card">
                            <div class="variant-title">
                                {{ $variant->sanPham->ten_san_pham }}
                            </div>
                            <div class="variant-meta">
                                <span class="variant-size-badge">{{ $variant->kich_thuoc }}</span>
                                <span class="variant-calo-info">{{ $variant->calo }} cal</span>
                            </div>
                            @if($variant->mo_ta)
                                <small class="text-muted">{{ Str::limit($variant->mo_ta, 60) }}</small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="variant-price-container">
                            <div class="variant-price-main">{{ number_format($variant->gia, 0, ',', '.') }}</div>
                        </div>
                    </td>
                    <td>
                        @php 
                            $stockStatus = $variant->stockStatus;
                            $stockClass = $stockStatus['class'] === 'success' ? 'in-stock' : ($stockStatus['class'] === 'warning' ? 'low-stock' : 'out-of-stock');
                        @endphp
                        <div class="variant-stock-container {{ $stockClass }}">
                            <div class="stock-icon {{ $stockClass }}"></div>
                            <div>
                                <div class="stock-text {{ $stockClass }}">{{ $stockStatus['text'] }}</div>
                                <div class="stock-count">{{ $variant->so_luong_ton }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="variant-status-toggle">
                            <input type="checkbox" 
                                   class="status-toggle-input" 
                                   id="status-{{ $variant->ma_bien_the }}"
                                   data-id="{{ $variant->ma_bien_the }}"
                                   {{ $variant->trang_thai ? 'checked' : '' }}>
                            <label class="status-slider" for="status-{{ $variant->ma_bien_the }}"></label>
                        </div>
                    </td>
                    <td>
                        <small class="text-muted">
                            {{ $variant->ngay_tao ? $variant->ngay_tao->format('d/m/Y') : 'N/A' }}
                            <br>
                            {{ $variant->ngay_tao ? $variant->ngay_tao->format('H:i') : '' }}
                        </small>
                    </td>
                    <td>
                        <div class="variant-actions-container">
                            <a href="{{ route('admin.product-variants.show', $variant->ma_bien_the) }}" 
                               class="variant-action-btn btn-view" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.product-variants.edit', $variant->ma_bien_the) }}" 
                               class="variant-action-btn btn-edit" title="Chỉnh sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" 
                                    class="variant-action-btn btn-delete delete-variant" 
                                    data-id="{{ $variant->ma_bien_the }}"
                                    data-name="{{ $variant->sanPham->ten_san_pham }} - {{ $variant->kich_thuoc }}"
                                    title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="variant-empty-state">
                            <div class="empty-icon">
                                <i class="fas fa-layer-group"></i>
                            </div>
                            <h5 class="empty-title">Không có biến thể sản phẩm nào</h5>
                            <p class="empty-message">Hãy thêm biến thể sản phẩm đầu tiên của bạn.</p>
                            <a href="{{ route('admin.product-variants.create') }}" class="empty-action">
                                <i class="fas fa-plus me-2"></i>
                                Thêm biến thể mới
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Enhanced Pagination -->
    @if($variants->hasPages())
    <div class="variant-pagination-wrapper">
        <div class="pagination-info">
            <span>Hiển thị {{ $variants->firstItem() }} - {{ $variants->lastItem() }} trên tổng {{ $variants->total() }} biến thể</span>
        </div>
        <div class="pagination-container">
            {{ $variants->links('admin.partials.pagination') }}
        </div>
    </div>
    @endif
</div>

<!-- Enhanced Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Xác nhận xóa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa biến thể sản phẩm:</p>
                <div class="alert alert-warning">
                    <strong id="variant-name"></strong>
                </div>
                <p class="text-danger"><small>Hành động này không thể hoàn tác!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash me-1"></i>
                    Xóa biến thể
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Enhanced status toggle with animation
    $('.status-toggle-input').on('change', function() {
        const toggle = $(this);
        const variantId = toggle.data('id');
        const isChecked = toggle.is(':checked');
        
        // Add loading state
        toggle.prop('disabled', true);
        toggle.closest('.variant-status-toggle').addClass('loading');
        
        $.ajax({
            url: `/admin/product-variants/${variantId}/toggle-status`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: isChecked ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    // Animate success
                    toggle.closest('.variant-status-toggle').removeClass('loading').addClass('success');
                    setTimeout(() => {
                        toggle.closest('.variant-status-toggle').removeClass('success');
                    }, 1000);
                    
                    // Show success message
                    showNotification('success', response.message);
                }
            },
            error: function() {
                // Revert toggle state
                toggle.prop('checked', !isChecked);
                showNotification('error', 'Có lỗi xảy ra khi cập nhật trạng thái');
            },
            complete: function() {
                toggle.prop('disabled', false);
                toggle.closest('.variant-status-toggle').removeClass('loading');
            }
        });
    });

    // Enhanced select all functionality
    $('#select-all').on('change', function() {
        const isChecked = $(this).is(':checked');
        $('.variant-checkbox').prop('checked', isChecked).trigger('change');
        updateBulkActionsVisibility();
    });

    // Individual checkbox change
    $('.variant-checkbox').on('change', function() {
        updateSelectAllState();
        updateBulkActionsVisibility();
    });

    // Enhanced bulk actions
    $('#bulk-action-btn').on('click', function() {
        const action = $('#bulk-action').val();
        const selectedIds = $('.variant-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            showNotification('warning', 'Vui lòng chọn ít nhất một biến thể');
            return;
        }

        if (!action) {
            showNotification('warning', 'Vui lòng chọn hành động');
            return;
        }

        // Show confirmation for destructive actions
        if (action === 'delete') {
            if (confirm(`Bạn có chắc chắn muốn xóa ${selectedIds.length} biến thể đã chọn?`)) {
                performBulkAction(action, selectedIds);
            }
        } else {
            performBulkAction(action, selectedIds);
        }
    });

    // Enhanced delete functionality
    $('.delete-variant').on('click', function() {
        const variantId = $(this).data('id');
        const variantName = $(this).data('name');
        
        $('#variant-name').text(variantName);
        $('#deleteModal').modal('show');
        
        $('#confirm-delete').off('click').on('click', function() {
            deleteVariant(variantId);
        });
    });

    // Enhanced search with debounce
    let searchTimeout;
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            $(this).closest('form').submit();
        }, 500);
    });

    // Filter form auto-submit
    $('#category-filter, #status-filter, #stock-filter').on('change', function() {
        $(this).closest('form').submit();
    });

    // Helper functions
    function updateSelectAllState() {
        const totalCheckboxes = $('.variant-checkbox').length;
        const checkedCheckboxes = $('.variant-checkbox:checked').length;
        
        $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
        $('#select-all').prop('indeterminate', checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes);
    }

    function updateBulkActionsVisibility() {
        const selectedCount = $('.variant-checkbox:checked').length;
        const bulkSection = $('.variant-bulk-section');
        
        if (selectedCount > 0) {
            bulkSection.addClass('show');
            $('#selected-count').text(selectedCount);
        } else {
            bulkSection.removeClass('show');
        }
    }

    function performBulkAction(action, ids) {
        // Add loading state
        $('#bulk-action-btn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xử lý...');
        
        $.ajax({
            url: '{{ route("admin.product-variants.bulk-action") }}',
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                action: action,
                ids: ids
            },
            success: function(response) {
                if (response.success) {
                    showNotification('success', response.message);
                    location.reload();
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra khi thực hiện hành động');
            },
            complete: function() {
                $('#bulk-action-btn').prop('disabled', false).html('<i class="fas fa-play me-1"></i> Thực hiện');
            }
        });
    }

    function deleteVariant(id) {
        $('#confirm-delete').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Đang xóa...');
        
        $.ajax({
            url: `/admin/product-variants/${id}`,
            method: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#deleteModal').modal('hide');
                    showNotification('success', response.message);
                    location.reload();
                }
            },
            error: function() {
                showNotification('error', 'Có lỗi xảy ra khi xóa biến thể');
            },
            complete: function() {
                $('#confirm-delete').prop('disabled', false).html('<i class="fas fa-trash me-1"></i> Xóa biến thể');
            }
        });
    }

    function showNotification(type, message) {
        // You can customize this to use your preferred notification system
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        
        const notification = $(`
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; max-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.alert('close');
        }, 3000);
    }
});
</script>
@endpush

@push('styles')
<style>
    .variant-status-toggle.loading .status-slider::before {
        animation: pulse 1.5s infinite;
    }
    
    .variant-status-toggle.success .status-slider {
        box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
</style>
@endpush