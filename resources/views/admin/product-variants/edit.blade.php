@extends('layouts.admin')

@section('title', 'Chỉnh sửa biến thể sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.product-variants.index') }}">Biến thể sản phẩm</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Chỉnh sửa biến thể sản phẩm</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.product-variants.index') }}">Biến thể sản phẩm</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
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
                        <i class="fas fa-edit me-2"></i>
                        Thông tin biến thể
                    </h6>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('admin.product-variants.update', $variant->ma_bien_the) }}" method="POST" enctype="multipart/form-data" id="variant-form">
                        @csrf
                        @method('PUT')
                        
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
                                        {{ old('ma_san_pham', $variant->ma_san_pham) == $product->ma_san_pham ? 'selected' : '' }}>
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
                                           value="{{ old('kich_thuoc', $variant->kich_thuoc) }}" 
                                           placeholder="Ví dụ: S, M, L, XL..." required>
                                    @error('kich_thuoc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gia_display" class="form-label fw-bold">
                                        <i class="fas fa-tag text-success me-2"></i>
                                        Giá (nghìn VNĐ) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="text" id="gia_display" 
                                               class="form-control @error('gia') is-invalid @enderror" 
                                               value="{{ old('gia') ? old('gia') / 1000 : $variant->gia / 1000 }}" 
                                               placeholder="Nhập số nghìn" required>
                                        <span class="input-group-text">x 1.000 VNĐ</span>
                                    </div>
                                    <input type="hidden" name="gia" id="gia" value="{{ old('gia', $variant->gia) }}">
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
                                               value="{{ old('calo', $variant->calo) }}" 
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
                                           value="{{ old('so_luong_ton', $variant->so_luong_ton) }}" 
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
                                       value="1" {{ old('trang_thai', $variant->trang_thai) ? 'checked' : '' }}>
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
                                Cập nhật biến thể
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
            <!-- Variant Info -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin biến thể
                    </h6>
                </div>
                <div class="p-4">
                    <div class="info-list">
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <strong>ID:</strong> 
                            <span class="badge bg-primary">{{ $variant->ma_bien_the }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <strong>Ngày tạo:</strong> 
                            <span class="text-muted">{{ $variant->ngay_tao ? $variant->ngay_tao->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <strong>Cập nhật cuối:</strong> 
                            <span class="text-muted">{{ $variant->ngay_cap_nhat ? $variant->ngay_cap_nhat->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Current Product Info -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-box me-2"></i>
                        Sản phẩm hiện tại
                    </h6>
                </div>
                <div class="p-4">
                    <div class="product-info">
                        <h6 class="mb-2">{{ $variant->sanPham->ten_san_pham }}</h6>
                        <p class="text-muted small mb-3">
                            <i class="fas fa-folder me-1"></i>
                            Danh mục: {{ $variant->sanPham->danhMuc->ten_danh_muc ?? 'N/A' }}
                        </p>
                        @if($variant->sanPham->hinh_anh)
                            <img src="{{ asset('images/products/' . $variant->sanPham->hinh_anh) }}" 
                                 alt="{{ $variant->sanPham->ten_san_pham }}" 
                                 class="img-fluid rounded" style="max-height: 120px;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 120px;">
                                <i class="fas fa-image fa-2x text-muted"></i>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Hướng dẫn
                    </h6>
                </div>
                <div class="p-4">
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-edit fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Chỉnh sửa</h6>
                                <p class="text-muted small mb-0">
                                    Bạn có thể cập nhật thông tin biến thể bao gồm kích thước, giá, calo và số lượng tồn.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Lưu ý</h6>
                                <p class="text-muted small mb-0">
                                    Việc thay đổi giá và tồn kho có thể ảnh hưởng đến đơn hàng đang xử lý.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<style>
.upload-area {
    background: #f8f9fa;
    border: 2px dashed #dee2e6 !important;
    transition: all 0.3s ease;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: #0d6efd !important;
    background: #e7f1ff;
}

.upload-area.dragover {
    border-color: #0d6efd !important;
    background: #e7f1ff;
    transform: scale(1.02);
}

.current-image-container img {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    border-radius: 8px;
}

.info-list .info-item:last-child {
    border-bottom: none !important;
}

.form-check-input:checked {
    background-color: #198754;
    border-color: #198754;
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.3s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
}

.table-container {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1rem 1.5rem;
}
</style>
@endpush

@push('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
});

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn reset form? Tất cả thay đổi sẽ bị mất.')) {
        location.reload();
    }
}
</script>
@endpush