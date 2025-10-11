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
                        <label for="product_filter">Sản phẩm</label>
                        <select class="form-control" id="product_filter" name="product_id">
                            <option value="">Tất cả sản phẩm</option>
                            @foreach($products ?? [] as $product)
                                <option value="{{ $product->ma_san_pham }}" {{ request('product_id') == $product->ma_san_pham ? 'selected' : '' }}>
                                    {{ $product->ten_san_pham }}
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
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.product-variants.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Xóa lọc
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
                    <li><a class="dropdown-item" href="#" onclick="toggleBulkActions()">
                        <i class="fas fa-tasks me-2"></i>Hành động hàng loạt
                    </a></li>
                </ul>
            </div>
        </div>
        
        @if($variants->count() > 0)
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

            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="select-all">
                            </th>
                            <th width="80">Hình ảnh</th>
                            <th>Thông tin biến thể</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($variants as $variant)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input variant-checkbox" value="{{ $variant->ma_bien_the }}">
                                </td>
                                <td>
                                    @if($variant->hinh_anh)
                                        <img src="{{ asset('images/variants/' . $variant->hinh_anh) }}" 
                                             alt="{{ $variant->sanPham->ten_san_pham }}" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px; border-radius: 0.375rem;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>{{ $variant->sanPham->ten_san_pham }}</strong>
                                            <div class="small text-muted">
                                                <span class="badge bg-info">{{ $variant->kich_thuoc }}</span>
                                                <span class="ms-2">{{ $variant->calo }} calo</span>
                                            </div>
                                            @if($variant->mo_ta)
                                                <small class="text-muted">{{ Str::limit($variant->mo_ta, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ number_format($variant->gia, 0, ',', '.') }}đ</strong>
                                </td>
                                <td>
                                    @php 
                                        $stockStatus = $variant->stockStatus;
                                        $badgeClass = $stockStatus['class'] === 'success' ? 'bg-success' : 
                                                     ($stockStatus['class'] === 'warning' ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $variant->so_luong_ton }}</span>
                                    <small class="d-block text-muted">{{ $stockStatus['text'] }}</small>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" 
                                               type="checkbox" 
                                               data-id="{{ $variant->ma_bien_the }}"
                                               {{ $variant->trang_thai ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            <span class="badge {{ $variant->trang_thai ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $variant->trangThaiText }}
                                            </span>
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $variant->ngay_tao ? $variant->ngay_tao->format('d/m/Y H:i') : 'Không có' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.product-variants.show', $variant->ma_bien_the) }}" 
                                           class="btn btn-outline-info btn-action" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.product-variants.edit', $variant->ma_bien_the) }}" 
                                           class="btn btn-outline-warning btn-action" 
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-action delete-variant" 
                                                data-id="{{ $variant->ma_bien_the }}"
                                                data-name="{{ $variant->sanPham->ten_san_pham }} - {{ $variant->kich_thuoc }}"
                                                title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-summary">
                    <div class="pagination-info">
                        Hiển thị <strong>{{ $variants->firstItem() ?? 0 }}</strong> đến <strong>{{ $variants->lastItem() ?? 0 }}</strong> 
                        trong tổng số <span class="badge">{{ number_format($variants->total()) }}</span> biến thể
                        @if(request()->has('search') || request()->has('product_id') || request()->has('status'))
                            <small class="text-muted ms-2">
                                <i class="fas fa-filter"></i> (đã lọc)
                            </small>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if($variants->hasPages())
                        <div class="pagination-controls">
                            <small class="text-muted me-2">Trang {{ $variants->currentPage() }} / {{ $variants->lastPage() }}</small>
                        </div>
                    @endif
                    {{ $variants->appends(request()->query())->links('custom.pagination') }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-layer-group fa-3x text-secondary mb-3"></i>
                <h5 class="text-secondary">Không tìm thấy biến thể nào</h5>
                <p class="text-muted">Hãy thêm biến thể đầu tiên cho sản phẩm của bạn.</p>
                <a href="{{ route('admin.product-variants.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm biến thể đầu tiên
                </a>
            </div>
        @endif
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

@section('scripts')
<script>
// Select all checkbox functionality
$('#select-all').change(function() {
    $('.variant-checkbox').prop('checked', this.checked);
    updateBulkActions();
});

// Individual checkbox change
$('.variant-checkbox').change(function() {
    updateBulkActions();
    
    // Update select all checkbox
    const totalCheckboxes = $('.variant-checkbox').length;
    const checkedCheckboxes = $('.variant-checkbox:checked').length;
    $('#select-all').prop('checked', checkedCheckboxes === totalCheckboxes);
});

// Update bulk actions visibility
function updateBulkActions() {
    const selectedCount = $('.variant-checkbox:checked').length;
    $('#selected-count').text(selectedCount);
    
    if (selectedCount > 0) {
        $('#bulk-actions-bar').slideDown();
    } else {
        $('#bulk-actions-bar').slideUp();
    }
}

// Toggle bulk actions
function toggleBulkActions() {
    $('#bulk-actions-bar').toggle();
}

// Clear selection
function clearSelection() {
    $('.variant-checkbox, #select-all').prop('checked', false);
    updateBulkActions();
}

// Status toggle
$('.status-toggle').change(function() {
    const variantId = $(this).data('id');
    const isActive = this.checked;
    const statusBadge = $(this).siblings('label').find('.badge');
    
    $.ajax({
        url: `/admin/product-variants/${variantId}/toggle-status`,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                statusBadge.removeClass('bg-success bg-secondary')
                          .addClass(response.status ? 'bg-success' : 'bg-secondary')
                          .text(response.status ? 'Hoạt động' : 'Không hoạt động');
                showToast('success', response.message);
            }
        },
        error: function() {
            // Revert checkbox state
            $(this).prop('checked', !isActive);
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
                location.reload();
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

// Bulk actions
function performBulkAction() {
    const selectedVariants = $('.variant-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    const action = $('#bulk-action').val();
    
    if (selectedVariants.length === 0) {
        showToast('warning', 'Vui lòng chọn ít nhất một biến thể');
        return;
    }
    
    if (!action) {
        showToast('warning', 'Vui lòng chọn hành động');
        return;
    }
    
    let confirmMessage = '';
    switch (action) {
        case 'activate':
            confirmMessage = `Bạn có chắc chắn muốn kích hoạt ${selectedVariants.length} biến thể đã chọn?`;
            break;
        case 'deactivate':
            confirmMessage = `Bạn có chắc chắn muốn vô hiệu hóa ${selectedVariants.length} biến thể đã chọn?`;
            break;
        case 'delete':
            confirmMessage = `Bạn có chắc chắn muốn xóa ${selectedVariants.length} biến thể đã chọn? Hành động này không thể hoàn tác!`;
            break;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }
    
    $.ajax({
        url: '{{ route("admin.product-variants.bulk-action") }}',
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            action: action,
            selected_variants: selectedVariants
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                showToast('error', response.message);
            }
        },
        error: function() {
            showToast('error', 'Có lỗi xảy ra khi thực hiện thao tác');
        }
    });
}

// Export function
function exportData() {
    alert('Chức năng xuất dữ liệu sẽ được triển khai sau');
}

// Auto submit form when filter changes
$('#product_filter, #status_filter').change(function() {
    this.form.submit();
});

// Toast notification function
function showToast(type, message) {
    // Implementation depends on your notification system
    console.log(`${type}: ${message}`);
}
</script>
@endsection