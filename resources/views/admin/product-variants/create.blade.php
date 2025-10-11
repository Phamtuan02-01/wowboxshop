@extends('layouts.admin')

@section('title', 'Thêm biến thể sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.product-variants.index') }}">Biến thể sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

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
                                        <input type="number" name="gia" id="gia" 
                                               class="form-control @error('gia') is-invalid @enderror" 
                                               value="{{ old('gia') }}" 
                                               placeholder="0" min="0" step="0.01" required>
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                    @error('gia')
                                        <div class="invalid-feedback">{{ $message }}</div>
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
                        
                        <!-- Description -->
                        <div class="form-group mb-4">
                            <label for="mo_ta" class="form-label fw-bold">
                                <i class="fas fa-align-left text-muted me-2"></i>
                                Mô tả biến thể
                            </label>
                            <textarea name="mo_ta" id="mo_ta" 
                                      class="form-control @error('mo_ta') is-invalid @enderror" 
                                      rows="4" placeholder="Mô tả chi tiết về biến thể sản phẩm...">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image text-primary me-2"></i>
                                Hình ảnh biến thể
                            </label>
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center" 
                                 style="cursor: pointer; transition: all 0.3s ease;" 
                                 onclick="document.getElementById('hinh_anh').click()">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">Nhấn để chọn hình ảnh</h6>
                                    <p class="text-muted mb-0">hoặc kéo thả file vào đây</p>
                                    <small class="text-muted">Hỗ trợ: JPG, PNG, GIF (tối đa 2MB)</small>
                                </div>
                            </div>
                            <input type="file" name="hinh_anh" id="hinh_anh" 
                                   class="d-none @error('hinh_anh') is-invalid @enderror" 
                                   accept="image/*">
                            @error('hinh_anh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            
                            <!-- Image Preview -->
                            <div id="image-preview" class="mt-3" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="preview-image" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                            style="transform: translate(50%, -50%);" onclick="removeImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
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

@section('scripts')
<script>
$(document).ready(function() {
    // Image upload functionality
    $('#hinh_anh').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Kích thước file không được vượt quá 2MB');
                this.value = '';
                return;
            }
            
            // Validate file type
            if (!file.type.match('image.*')) {
                alert('Vui lòng chọn file hình ảnh');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-image').attr('src', e.target.result);
                $('#image-preview').show();
                $('.upload-area').hide();
            };
            reader.readAsDataURL(file);
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
        }
    });
    
    // Auto-format price input
    $('#gia').on('input', function() {
        let value = $(this).val().replace(/[^0-9]/g, '');
        $(this).val(value);
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
    
    // Drag and drop for image upload
    $('.upload-area').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('border-primary bg-light');
    });
    
    $('.upload-area').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('border-primary bg-light');
    });
    
    $('.upload-area').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('border-primary bg-light');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#hinh_anh')[0].files = files;
            $('#hinh_anh').trigger('change');
        }
    });
    
    // Load product info on page load if selected
    const selectedProductId = $('#ma_san_pham').val();
    if (selectedProductId) {
        loadProductInfo(selectedProductId);
        $('#product-info-card').show();
    }
});

function removeImage() {
    $('#hinh_anh').val('');
    $('#image-preview').hide();
    $('.upload-area').show();
}

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn reset form? Tất cả dữ liệu đã nhập sẽ bị mất.')) {
        document.getElementById('variant-form').reset();
        $('#image-preview').hide();
        $('.upload-area').show();
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
    
    // Simulate AJAX call - replace with actual API call if needed
    setTimeout(function() {
        $('#product-details').html(`
            <div class="text-center py-3">
                <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                <h6 class="mb-2">Sản phẩm đã được chọn</h6>
                <p class="text-muted small mb-0">Vui lòng điền thông tin biến thể.</p>
            </div>
        `);
    }, 1000);
}
</script>
@endsection