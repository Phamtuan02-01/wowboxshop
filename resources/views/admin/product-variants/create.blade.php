@extends('layouts.admin')

@section('title', 'Thêm biến thể sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.product-variants.index') }}">Biến thể sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Thêm biến thể sản phẩm</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product-variants.index') }}">Biến thể sản phẩm</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.product-variants.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-layer-group me-2"></i>
                        Thông tin biến thể
                    </h6>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('admin.product-variants.store') }}" method="POST" enctype="multipart/form-data" id="variant-form">
                        @csrf
                        
                        <!-- Product Selection -->
                        <div class="form-group mb-4">
                            <label for="ma_san_pham" class="form-label fw-bold">
                                <i class="fas fa-box text-primary me-2"></i>
                                Sản phẩm <span class="text-danger">*</span>
                            </label>
                            <select name="ma_san_pham" id="ma_san_pham" class="form-control @error('ma_san_pham') is-invalid @enderror" required>
                                <option value="">-- Chọn sản phẩm --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->ma_san_pham }}" 
                                        {{ old('ma_san_pham', $selectedProduct?->ma_san_pham) == $product->ma_san_pham ? 'selected' : '' }}>
                                        {{ $product->ten_san_pham }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ma_san_pham')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Size -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="kich_thuoc" class="form-label fw-bold">
                                        <i class="fas fa-ruler text-info me-2"></i>
                                        Kích thước <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="kich_thuoc" id="kich_thuoc" 
                                           class="form-control @error('kich_thuoc') is-invalid @enderror" 
                                           value="{{ old('kich_thuoc') }}" 
                                           placeholder="Ví dụ: S, M, L, XL..." required>
                                    @error('kich_thuoc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gia" class="form-label fw-bold">
                                        <i class="fas fa-tag text-success me-2"></i>
                                        Giá <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="gia_display" id="gia_display" 
                                               class="form-control @error('gia') is-invalid @enderror" 
                                               value="{{ old('gia') }}" 
                                               placeholder="Nhập số ngàn (VD: 10 = 10.000đ)" required>
                                        <span class="input-group-text">x 1.000 VNĐ</span>
                                        <input type="hidden" name="gia" id="gia" value="{{ old('gia') }}">
                                    </div>
                                    <small class="text-muted">Nhập số ngàn: VD nhập 10 sẽ thành 10.000đ</small>
                                    @error('gia')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Calories -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="calo" class="form-label fw-bold">
                                        <i class="fas fa-fire text-warning me-2"></i>
                                        Calo <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="calo" id="calo" 
                                               class="form-control @error('calo') is-invalid @enderror" 
                                               value="{{ old('calo') }}" 
                                               placeholder="0" min="0" required>
                                        <span class="input-group-text">cal</span>
                                    </div>
                                    @error('calo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Stock -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="so_luong_ton" class="form-label fw-bold">
                                        <i class="fas fa-boxes text-secondary me-2"></i>
                                        Số lượng tồn <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="so_luong_ton" id="so_luong_ton" 
                                           class="form-control @error('so_luong_ton') is-invalid @enderror" 
                                           value="{{ old('so_luong_ton', 0) }}" 
                                           placeholder="0" min="0" required>
                                    @error('so_luong_ton')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Toggle -->
                        <div class="form-group mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai" 
                                       value="1" {{ old('trang_thai', true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="trang_thai">
                                    <i class="fas fa-toggle-on text-success me-2"></i>
                                    Kích hoạt biến thể
                                </label>
                                <small class="form-text text-muted d-block">
                                    Biến thể sẽ hiển thị trên website khi được kích hoạt
                                </small>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Lưu biến thể
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i>
                                Reset
                            </button>
                            <a href="{{ route('admin.product-variants.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i>
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Hướng dẫn
                    </h6>
                </div>
                <div class="p-4">
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-layer-group fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Biến thể sản phẩm</h6>
                                <p class="text-muted small mb-0">
                                    Biến thể cho phép bạn tạo các phiên bản khác nhau của cùng một sản phẩm 
                                    với kích thước, giá cả và thông tin khác nhau.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-ruler fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Kích thước</h6>
                                <p class="text-muted small mb-0">
                                    Nhập kích thước rõ ràng như S, M, L, XL hoặc 250ml, 500ml, 1L...
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-fire fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Calo</h6>
                                <p class="text-muted small mb-0">
                                    Thông tin dinh dưỡng quan trọng giúp khách hàng đưa ra quyết định mua hàng.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-boxes fa-2x text-secondary"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Tồn kho</h6>
                                <p class="text-muted small mb-0">
                                    Quản lý số lượng tồn kho chính xác để tránh tình trạng bán quá số lượng có sẵn.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Product Info -->
            <div id="product-info-card" class="table-container mt-4" style="display: none;">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-box me-2"></i>
                        Thông tin sản phẩm
                    </h6>
                </div>
                <div class="p-4">
                    <div id="product-details" class="text-center">
                        <!-- Product details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('scripts')
<script>
$(document).ready(function() {
    // Initialize Select2 for product selection
    $('#ma_san_pham').select2({
        theme: 'bootstrap-5',
        placeholder: 'Chọn sản phẩm',
        allowClear: true,
        language: {
            noResults: function() {
                return "Không tìm thấy sản phẩm";
            },
            searching: function() {
                return "Đang tìm kiếm...";
            }
        }
    });

    // Product selection change
    $('#ma_san_pham').change(function() {
        const productId = $(this).val();
        if (productId) {
            loadProductInfo(productId);
            $('#product-info-card').fadeIn();
        } else {
            $('#product-info-card').fadeOut();
            // Clear auto-filled values when product is deselected
            $('#kich_thuoc').val('');
            $('#gia_display').val('');
            $('#gia').val('');
        }
    });
    
    // Auto-format price input (multiply by 1000)
    $('#gia_display').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
        
        // Convert to actual price (multiply by 1000)
        if (value) {
            $('#gia').val(value * 1000);
        } else {
            $('#gia').val('');
        }
    });
    
    // Handle form submission to ensure gia is set
    $('#variant-form').on('submit', function() {
        const displayValue = $('#gia_display').val().replace(/[^0-9]/g, '');
        if (displayValue) {
            $('#gia').val(displayValue * 1000);
        }
    });
    
    // Form validation
    $('#variant-form').submit(function(e) {
        let isValid = true;
        
        // Check required fields
        $(this).find('[required]').each(function() {
            if (!$(this).val().trim()) {
                isValid = false;
                $(this).addClass('is-invalid');
                $(this).focus();
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng điền đầy đủ thông tin bắt buộc');
        }
    });
    
    // Load product info on page load if selected
    const selectedProductId = $('#ma_san_pham').val();
    if (selectedProductId) {
        loadProductInfo(selectedProductId);
        $('#product-info-card').show();
    }
});

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn reset form? Tất cả dữ liệu đã nhập sẽ bị mất.')) {
        document.getElementById('variant-form').reset();
        $('#product-info-card').hide();
        $('.is-invalid').removeClass('is-invalid');
    }
}

function loadProductInfo(productId) {
    $('#product-details').html(`
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 mb-0 text-muted">Đang tải thông tin sản phẩm...</p>
        </div>
    `);
    
    // Fetch product information via AJAX
    $.ajax({
        url: '{{ route("admin.products.get-info", ":id") }}'.replace(':id', productId),
        method: 'GET',
        success: function(response) {
            const product = response.product;
            const variantCount = response.variant_count;
            
            // Display product info
            let productHtml = `
                <div class="text-start">
                    <h6 class="mb-3">${product.ten_san_pham}</h6>
                    <div class="mb-2">
                        <small class="text-muted">Giá gốc:</small>
                        <div class="fw-bold text-success">${formatNumber(product.gia)} VNĐ</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Danh mục:</small>
                        <div>${product.danh_muc ? product.danh_muc.ten_danh_muc : 'Chưa có'}</div>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Số biến thể hiện có:</small>
                        <div class="badge bg-info">${variantCount}</div>
                    </div>
                </div>
            `;
            
            $('#product-details').html(productHtml);
            
            // If this is the first variant, auto-fill size and price
            if (variantCount === 0) {
                $('#kich_thuoc').val('S');
                const priceInThousands = Math.floor(product.gia / 1000);
                $('#gia_display').val(priceInThousands);
                $('#gia').val(product.gia);
                
                // Show notification
                showNotification('Đây là biến thể đầu tiên! Đã tự động điền kích thước "S" và giá từ sản phẩm.', 'info');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.error('Response:', xhr.responseText);
            $('#product-details').html(`
                <div class="text-center py-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-2"></i>
                    <h6 class="mb-2">Không thể tải thông tin</h6>
                    <p class="text-muted small mb-0">Vui lòng thử lại. ${error}</p>
                </div>
            `);
        }
    });
}

function formatNumber(num) {
    return new Intl.NumberFormat('vi-VN').format(num);
}

function showNotification(message, type = 'success') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'info' ? 'alert-info' : 
                      type === 'warning' ? 'alert-warning' : 'alert-danger';
    
    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 80px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(function() {
        notification.fadeOut(function() {
            $(this).remove();
        });
    }, 5000);
}
</script>
@endsection