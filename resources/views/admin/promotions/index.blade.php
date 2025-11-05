@extends('layouts.admin')

@section('title', 'Quản lý khuyến mãi')

@section('breadcrumb')
    <li class="breadcrumb-item active">Khuyến mãi</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Quản lý khuyến mãi</h1>
                <p class="text-muted">Tạo và quản lý các chương trình khuyến mãi</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo khuyến mãi mới
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Tổng khuyến mãi</p>
                            <h3 class="stat-value text-primary">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-tags fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Đang hoạt động</p>
                            <h3 class="stat-value text-success">{{ $stats['active'] }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-check-circle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Đã hết hạn</p>
                            <h3 class="stat-value text-danger">{{ $stats['expired'] }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-times-circle fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Sắp diễn ra</p>
                            <h3 class="stat-value text-warning">{{ $stats['upcoming'] }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-clock fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="table-container">
        <div class="table-header">
            <h6 class="section-title mb-0">
                <i class="fas fa-filter me-2"></i>
                Bộ lọc và tìm kiếm
            </h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.promotions.index') }}" id="filter-form">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select" onchange="submitFilter()">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Đã tắt</option>
                            <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Đã hết hạn</option>
                            <option value="upcoming" {{ request('status') === 'upcoming' ? 'selected' : '' }}>Sắp diễn ra</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Loại khuyến mãi</label>
                        <select name="type" class="form-select" onchange="submitFilter()">
                            <option value="">Tất cả loại</option>
                            <option value="percent" {{ request('type') === 'percent' ? 'selected' : '' }}>Giảm theo %</option>
                            <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Giảm số tiền cố định</option>
                            <option value="product_discount" {{ request('type') === 'product_discount' ? 'selected' : '' }}>Giảm giá sản phẩm</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tìm kiếm</label>
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tên khuyến mãi, mã code..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-grid">
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Promotions Table -->
    <div class="table-container">
        <div class="table-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="section-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Danh sách khuyến mãi ({{ $promotions->total() }} kết quả)
                </h6>
                <div class="bulk-actions" style="display: none;">
                    <select id="bulk-action" class="form-select form-select-sm" style="width: auto;">
                        <option value="">Chọn thao tác</option>
                        <option value="activate">Kích hoạt</option>
                        <option value="deactivate">Tắt</option>
                        <option value="delete">Xóa</option>
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
                        <th>Hình ảnh</th>
                        <th>Thông tin khuyến mãi</th>
                        <th>Mã code</th>
                        <th>Loại & Giá trị</th>
                        <th>Thời gian</th>
                        <th>Sử dụng</th>
                        <th>Trạng thái</th>
                        <th width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($promotions as $promotion)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input item-checkbox" 
                                   value="{{ $promotion->ma_khuyen_mai }}">
                        </td>
                        <td>
                            @if($promotion->hinh_anh)
                                <img src="{{ asset('images/promotions/' . $promotion->hinh_anh) }}" 
                                     alt="{{ $promotion->ten_khuyen_mai }}" 
                                     class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-tags text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>
                                <h6 class="mb-1">{{ $promotion->ten_khuyen_mai }}</h6>
                                @if($promotion->mo_ta)
                                    <small class="text-muted">{{ Str::limit($promotion->mo_ta, 60) }}</small>
                                @endif
                                <br>
                                <small class="text-muted">
                                    ID: {{ $promotion->ma_khuyen_mai }}
                                </small>
                            </div>
                        </td>
                        <td>
                            @if($promotion->ma_code)
                                <span class="badge bg-secondary">{{ $promotion->ma_code }}</span>
                                <button type="button" class="btn btn-sm btn-outline-primary ms-1" 
                                        onclick="copyCode('{{ $promotion->ma_code }}')">
                                    <i class="fas fa-copy"></i>
                                </button>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <span class="badge bg-info">{{ $promotion->loai_khuyen_mai_text }}</span>
                                <br>
                                <strong class="text-success">{{ $promotion->gia_tri_display }}</strong>
                                @if($promotion->gia_tri_toi_da)
                                    <br><small class="text-muted">Tối đa: {{ number_format($promotion->gia_tri_toi_da, 0, ',', '.') }} VNĐ</small>
                                @endif
                                @if($promotion->don_hang_toi_thieu > 0)
                                    <br><small class="text-muted">Đơn tối thiểu: {{ number_format($promotion->don_hang_toi_thieu, 0, ',', '.') }} VNĐ</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div>
                                <small class="text-muted">Bắt đầu:</small><br>
                                <strong>{{ $promotion->ngay_bat_dau->format('d/m/Y H:i') }}</strong>
                                <br>
                                <small class="text-muted">Kết thúc:</small><br>
                                <strong>{{ $promotion->ngay_ket_thuc->format('d/m/Y H:i') }}</strong>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>{{ $promotion->so_luong_su_dung }}</strong>
                                @if($promotion->gioi_han_su_dung)
                                    / {{ $promotion->gioi_han_su_dung }}
                                @else
                                    / ∞
                                @endif
                                <br>
                                <small class="text-muted">
                                    Mỗi KH: {{ $promotion->gioi_han_moi_khach }} lần
                                </small>
                            </div>
                        </td>
                        <td>
                            @php
                                $statusClass = 'secondary';
                                $statusText = $promotion->trang_thai_text;
                                if ($promotion->trang_thai_text === 'Đang hoạt động') $statusClass = 'success';
                                elseif ($promotion->trang_thai_text === 'Đã kết thúc') $statusClass = 'danger';
                                elseif ($promotion->trang_thai_text === 'Chưa bắt đầu') $statusClass = 'warning';
                                elseif ($promotion->trang_thai_text === 'Đã tắt') $statusClass = 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                            <br>
                            <div class="form-check form-switch mt-1">
                                <input class="form-check-input" type="checkbox" 
                                       data-promotion-id="{{ $promotion->ma_khuyen_mai }}"
                                       {{ $promotion->trang_thai ? 'checked' : '' }}
                                       onchange="toggleStatus({{ $promotion->ma_khuyen_mai }}, this)">
                            </div>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.promotions.show', $promotion) }}" 
                                   class="btn btn-sm btn-outline-info" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.promotions.edit', $promotion) }}" 
                                   class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                        data-promotion-id="{{ $promotion->ma_khuyen_mai }}"
                                        onclick="deletePromotion({{ $promotion->ma_khuyen_mai }})" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Chưa có khuyến mãi nào</h5>
                            <p class="text-muted">Hãy tạo khuyến mãi đầu tiên cho shop của bạn!</p>
                            <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tạo khuyến mãi mới
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($promotions->hasPages())
        <div class="table-footer">
            {{ $promotions->appends(request()->query())->links('custom.pagination') }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    console.log('Promotions index page loaded');
    console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));
    
    // Select all checkbox
    $('#select-all').change(function() {
        $('.item-checkbox').prop('checked', this.checked);
        toggleBulkActions();
    });
    
    // Individual checkbox
    $('.item-checkbox').change(function() {
        toggleBulkActions();
        
        // Update select all checkbox
        const totalCheckboxes = $('.item-checkbox').length;
        const checkedCheckboxes = $('.item-checkbox:checked').length;
        $('#select-all').prop('checked', totalCheckboxes === checkedCheckboxes);
    });
    
    // Test if jQuery and Bootstrap are available
    console.log('jQuery version:', $.fn.jquery);
    console.log('Bootstrap available:', typeof bootstrap !== 'undefined');
    
    // Test AJAX setup
    console.log('AJAX headers:', $.ajaxSettings.headers);
});

function submitFilter() {
    $('#filter-form').submit();
}

function toggleBulkActions() {
    const checkedItems = $('.item-checkbox:checked').length;
    if (checkedItems > 0) {
        $('.bulk-actions').show();
    } else {
        $('.bulk-actions').hide();
    }
}

function executeBulkAction() {
    const action = $('#bulk-action').val();
    const selectedItems = $('.item-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    if (!action) {
        showToast('error', 'Vui lòng chọn thao tác!');
        return;
    }
    
    if (selectedItems.length === 0) {
        showToast('error', 'Vui lòng chọn ít nhất một khuyến mãi!');
        return;
    }
    
    let confirmMessage = '';
    switch (action) {
        case 'activate':
            confirmMessage = `Bạn có chắc chắn muốn kích hoạt ${selectedItems.length} khuyến mãi đã chọn?`;
            break;
        case 'deactivate':
            confirmMessage = `Bạn có chắc chắn muốn tắt ${selectedItems.length} khuyến mãi đã chọn?`;
            break;
        case 'delete':
            confirmMessage = `Bạn có chắc chắn muốn xóa ${selectedItems.length} khuyến mãi đã chọn? Hành động này không thể hoàn tác!`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        // Disable the bulk action button
        const bulkButton = $('button[onclick="executeBulkAction()"]');
        const originalText = bulkButton.text();
        bulkButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Đang xử lý...');
        
        $.ajax({
            url: '{{ route("admin.promotions.bulk-action") }}',
            method: 'POST',
            data: {
                action: action,
                selected_items: selectedItems,
                _token: '{{ csrf_token() }}'
            },
            timeout: 15000, // 15 second timeout for bulk operations
            success: function(response) {
                console.log('Bulk action response:', response);
                
                if (response && response.success) {
                    showToast('success', response.message);
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showToast('error', 'Có lỗi xảy ra khi thực hiện thao tác!');
                    bulkButton.prop('disabled', false).text(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error('Bulk action error:', {xhr, status, error});
                
                let errorMessage = 'Có lỗi xảy ra khi thực hiện thao tác!';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (status === 'timeout') {
                    errorMessage = 'Yêu cầu bị hết thời gian. Vui lòng thử lại!';
                }
                
                showToast('error', errorMessage);
                bulkButton.prop('disabled', false).text(originalText);
            }
        });
    }
}

function toggleStatus(promotionId, checkbox) {
    console.log('=== Toggle Status Function Called ===');
    console.log('Promotion ID:', promotionId);
    console.log('Checkbox element:', checkbox);
    console.log('Current checked state:', checkbox.checked);
    
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        alert('jQuery chưa được load!');
        checkbox.checked = !checkbox.checked;
        return;
    }
    
    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken ? 'Found' : 'NOT FOUND');
    
    if (!csrfToken) {
        alert('Không tìm thấy CSRF token!');
        checkbox.checked = !checkbox.checked;
        return;
    }
    
    // Disable checkbox during request
    checkbox.disabled = true;
    
    const baseUrl = '{{ url("/") }}';
    const url = `${baseUrl}/admin/promotions/${promotionId}/toggle-status`;
    console.log('Toggle status URL:', url);
    console.log('Request method: POST (with _method=PATCH)');
    
    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _method: 'PATCH'
        },
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        timeout: 10000,
        beforeSend: function(xhr) {
            console.log('Sending AJAX request...');
            console.log('Headers:', xhr.getAllResponseHeaders());
        },
        success: function(response) {
            console.log('Toggle status SUCCESS response:', response);
            
            if (response && response.success) {
                showToast('success', response.message);
                
                // Reload page after 1 second
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            } else {
                console.warn('Response success is false');
                // Revert checkbox state
                checkbox.checked = !checkbox.checked;
                checkbox.disabled = false;
                showToast('error', 'Có lỗi xảy ra khi cập nhật trạng thái!');
            }
        },
        error: function(xhr, status, error) {
            console.error('=== Toggle status ERROR ===');
            console.error('XHR Status:', xhr.status);
            console.error('XHR Status Text:', xhr.statusText);
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            console.error('Response JSON:', xhr.responseJSON);
            
            // Revert checkbox state
            checkbox.checked = !checkbox.checked;
            checkbox.disabled = false;
            
            let errorMessage = 'Có lỗi xảy ra khi cập nhật trạng thái!';
            let debugInfo = `\nStatus Code: ${xhr.status}\nStatus: ${status}\nError: ${error}`;
            
            if (xhr.status === 419) {
                errorMessage = 'Phiên làm việc đã hết hạn (419 CSRF). Vui lòng tải lại trang!';
            } else if (xhr.status === 404) {
                errorMessage = 'Không tìm thấy khuyến mãi (404)!';
            } else if (xhr.status === 500) {
                errorMessage = 'Lỗi server (500) khi cập nhật trạng thái!';
            } else if (xhr.status === 405) {
                errorMessage = 'Method không được phép (405). Route có thể chưa đúng!';
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (status === 'timeout') {
                errorMessage = 'Yêu cầu bị hết thời gian. Vui lòng thử lại!';
            } else if (status === 'error' && xhr.status === 0) {
                errorMessage = 'Không thể kết nối đến server. Kiểm tra URL hoặc CORS!';
            } else if (xhr.responseText) {
                debugInfo += `\nResponse: ${xhr.responseText.substring(0, 200)}`;
            }
            
            // Show detailed error in console
            console.error('Full error details:', {
                status: xhr.status,
                statusText: xhr.statusText,
                responseText: xhr.responseText,
                responseJSON: xhr.responseJSON,
                url: `/admin/promotions/${promotionId}/toggle-status`
            });
            
            alert('❌ ' + errorMessage + debugInfo);
        }
    });
}

function deletePromotion(promotionId) {
    console.log('=== Delete Promotion Function Called ===');
    console.log('Promotion ID:', promotionId);
    
    // Check if jQuery is available
    if (typeof $ === 'undefined') {
        alert('jQuery chưa được load!');
        return;
    }
    
    // Get CSRF token
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log('CSRF Token:', csrfToken ? 'Found' : 'NOT FOUND');
    
    if (!csrfToken) {
        alert('Không tìm thấy CSRF token!');
        return;
    }
    
    if (confirm('Bạn có chắc chắn muốn xóa khuyến mãi này? Hành động này không thể hoàn tác!')) {
        // Disable button to prevent double click
        const button = event.target.closest('button');
        const originalContent = button ? button.innerHTML : '';
        
        if (button) {
            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }
        
        const baseUrl = '{{ url("/") }}';
        const url = `${baseUrl}/admin/promotions/${promotionId}`;
        console.log('DELETE URL:', url);
        console.log('Request method: POST (with _method=DELETE)');
        
        $.ajax({
            url: url,
            method: 'POST',
            data: {
                _method: 'DELETE'
            },
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            timeout: 10000, // 10 second timeout
            beforeSend: function(xhr) {
                console.log('Sending DELETE request...');
            },
            success: function(response) {
                console.log('Delete SUCCESS response:', response);
                
                if (response && response.success) {
                    showToast('success', response.message || 'Khuyến mãi đã được xóa thành công!');
                } else {
                    showToast('success', 'Khuyến mãi đã được xóa thành công!');
                }
                
                // Reload page after a short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            },
            error: function(xhr, status, error) {
                console.error('=== Delete Promotion ERROR ===');
                console.error('XHR Status:', xhr.status);
                console.error('XHR Status Text:', xhr.statusText);
                console.error('Status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Response JSON:', xhr.responseJSON);
                
                let errorMessage = 'Có lỗi xảy ra khi xóa khuyến mãi!';
                let debugInfo = `\nStatus Code: ${xhr.status}\nStatus: ${status}\nError: ${error}`;
                
                if (xhr.status === 419) {
                    errorMessage = 'Phiên làm việc đã hết hạn (419 CSRF). Vui lòng tải lại trang!';
                } else if (xhr.status === 404) {
                    errorMessage = 'Không tìm thấy khuyến mãi cần xóa (404)!';
                } else if (xhr.status === 500) {
                    errorMessage = 'Lỗi server (500) khi xóa khuyến mãi!';
                } else if (xhr.status === 405) {
                    errorMessage = 'Method không được phép (405). Route có thể chưa đúng!';
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (status === 'timeout') {
                    errorMessage = 'Yêu cầu bị hết thời gian. Vui lòng thử lại!';
                } else if (status === 'error' && xhr.status === 0) {
                    errorMessage = 'Không thể kết nối đến server. Kiểm tra URL hoặc CORS!';
                } else if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                            debugInfo += `\nMessage: ${response.message}`;
                        }
                    } catch (e) {
                        debugInfo += `\nResponse: ${xhr.responseText.substring(0, 200)}`;
                    }
                }
                
                // Show detailed error in console
                console.error('Full error details:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText,
                    responseJSON: xhr.responseJSON,
                    url: `/admin/promotions/${promotionId}`
                });
                
                alert('❌ ' + errorMessage + debugInfo);
                
                // Re-enable button
                if (button) {
                    button.disabled = false;
                    button.innerHTML = originalContent;
                }
            }
        });
    }
}

function copyCode(code) {
    // Simple fallback method that works everywhere
    const textArea = document.createElement("textarea");
    textArea.value = code;
    textArea.style.position = "fixed";
    textArea.style.opacity = "0";
    textArea.style.left = "-9999px";
    
    document.body.appendChild(textArea);
    textArea.select();
    textArea.setSelectionRange(0, 99999);
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showToast('success', 'Đã sao chép mã: ' + code);
        } else {
            showToast('error', 'Không thể sao chép mã. Vui lòng sao chép thủ công: ' + code);
        }
    } catch (err) {
        console.error('Copy failed:', err);
        showToast('error', 'Không thể sao chép mã. Vui lòng sao chép thủ công: ' + code);
    }
    
    document.body.removeChild(textArea);
}

function showToast(type, message) {
    // Use simple alert for better compatibility
    if (type === 'success') {
        alert('✅ ' + message);
    } else {
        alert('❌ ' + message);
    }
}
</script>
@endpush