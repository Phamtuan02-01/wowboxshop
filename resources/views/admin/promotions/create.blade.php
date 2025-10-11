@extends('layouts.admin')

@section('title', 'Tạo khuyến mãi mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Khuyến mãi</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Tạo khuyến mãi mới</h1>
                <p class="text-muted">Tạo chương trình khuyến mãi để thu hút khách hàng</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
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
                        <i class="fas fa-plus me-2"></i>
                        Thông tin khuyến mãi
                    </h6>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('admin.promotions.store') }}" method="POST" enctype="multipart/form-data" id="promotion-form">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="form-group mb-4">
                            <label for="ten_khuyen_mai" class="form-label fw-bold">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Tên khuyến mãi <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ten_khuyen_mai" id="ten_khuyen_mai" 
                                   class="form-control @error('ten_khuyen_mai') is-invalid @enderror" 
                                   value="{{ old('ten_khuyen_mai') }}" 
                                   placeholder="Ví dụ: Giảm giá mùa hè 2024" required>
                            @error('ten_khuyen_mai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="mo_ta" class="form-label fw-bold">
                                <i class="fas fa-align-left text-muted me-2"></i>
                                Mô tả khuyến mãi
                            </label>
                            <textarea name="mo_ta" id="mo_ta" 
                                      class="form-control @error('mo_ta') is-invalid @enderror" 
                                      rows="4" placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <!-- Promotion Code -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ma_code" class="form-label fw-bold">
                                        <i class="fas fa-code text-info me-2"></i>
                                        Mã khuyến mãi
                                    </label>
                                    <div class="input-group">
                                        <input type="text" name="ma_code" id="ma_code" 
                                               class="form-control @error('ma_code') is-invalid @enderror" 
                                               value="{{ old('ma_code') }}" 
                                               placeholder="Để trống để tự động tạo">
                                        <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                            <i class="fas fa-dice"></i>
                                        </button>
                                    </div>
                                    <small class="form-text text-muted">
                                        Khách hàng sẽ nhập mã này để áp dụng khuyến mãi
                                    </small>
                                    @error('ma_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Promotion Type -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="loai_khuyen_mai" class="form-label fw-bold">
                                        <i class="fas fa-percentage text-success me-2"></i>
                                        Loại khuyến mãi <span class="text-danger">*</span>
                                    </label>
                                    <select name="loai_khuyen_mai" id="loai_khuyen_mai" 
                                            class="form-select @error('loai_khuyen_mai') is-invalid @enderror" required>
                                        <option value="">-- Chọn loại khuyến mãi --</option>
                                        <option value="percent" {{ old('loai_khuyen_mai') === 'percent' ? 'selected' : '' }}>
                                            Giảm theo phần trăm (%)
                                        </option>
                                        <option value="fixed" {{ old('loai_khuyen_mai') === 'fixed' ? 'selected' : '' }}>
                                            Giảm số tiền cố định (VNĐ)
                                        </option>
                                        <option value="product_discount" {{ old('loai_khuyen_mai') === 'product_discount' ? 'selected' : '' }}>
                                            Giảm giá sản phẩm cụ thể
                                        </option>
                                    </select>
                                    @error('loai_khuyen_mai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Discount Value -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gia_tri" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-warning me-2"></i>
                                        Giá trị giảm <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="gia_tri" id="gia_tri" 
                                               class="form-control @error('gia_tri') is-invalid @enderror" 
                                               value="{{ old('gia_tri') }}" 
                                               placeholder="0" min="0" step="0.01" required>
                                        <span class="input-group-text" id="discount-unit">%</span>
                                    </div>
                                    @error('gia_tri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Max Discount Value -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gia_tri_toi_da" class="form-label fw-bold">
                                        <i class="fas fa-chart-line text-warning me-2"></i>
                                        Giá trị giảm tối đa
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="gia_tri_toi_da" id="gia_tri_toi_da" 
                                               class="form-control @error('gia_tri_toi_da') is-invalid @enderror" 
                                               value="{{ old('gia_tri_toi_da') }}" 
                                               placeholder="0" min="0" step="0.01">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                    <small class="form-text text-muted">
                                        Chỉ áp dụng cho loại giảm theo phần trăm
                                    </small>
                                    @error('gia_tri_toi_da')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Minimum Order Value -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="don_hang_toi_thieu" class="form-label fw-bold">
                                        <i class="fas fa-shopping-cart text-secondary me-2"></i>
                                        Đơn hàng tối thiểu
                                    </label>
                                    <div class="input-group">
                                        <input type="number" name="don_hang_toi_thieu" id="don_hang_toi_thieu" 
                                               class="form-control @error('don_hang_toi_thieu') is-invalid @enderror" 
                                               value="{{ old('don_hang_toi_thieu', 0) }}" 
                                               placeholder="0" min="0" step="0.01">
                                        <span class="input-group-text">VNĐ</span>
                                    </div>
                                    @error('don_hang_toi_thieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Usage Limit -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gioi_han_su_dung" class="form-label fw-bold">
                                        <i class="fas fa-users text-info me-2"></i>
                                        Giới hạn sử dụng
                                    </label>
                                    <input type="number" name="gioi_han_su_dung" id="gioi_han_su_dung" 
                                           class="form-control @error('gioi_han_su_dung') is-invalid @enderror" 
                                           value="{{ old('gioi_han_su_dung') }}" 
                                           placeholder="Để trống = không giới hạn" min="1">
                                    <small class="form-text text-muted">
                                        Tổng số lần có thể sử dụng khuyến mãi này
                                    </small>
                                    @error('gioi_han_su_dung')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="gioi_han_moi_khach" class="form-label fw-bold">
                                <i class="fas fa-user text-info me-2"></i>
                                Giới hạn mỗi khách hàng <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="gioi_han_moi_khach" id="gioi_han_moi_khach" 
                                   class="form-control @error('gioi_han_moi_khach') is-invalid @enderror" 
                                   value="{{ old('gioi_han_moi_khach', 1) }}" 
                                   placeholder="1" min="1" required>
                            <small class="form-text text-muted">
                                Số lần tối đa mỗi khách hàng có thể sử dụng
                            </small>
                            @error('gioi_han_moi_khach')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Time Period -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ngay_bat_dau" class="form-label fw-bold">
                                        <i class="fas fa-calendar-plus text-success me-2"></i>
                                        Ngày bắt đầu <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" name="ngay_bat_dau" id="ngay_bat_dau" 
                                           class="form-control @error('ngay_bat_dau') is-invalid @enderror" 
                                           value="{{ old('ngay_bat_dau') }}" required>
                                    @error('ngay_bat_dau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="ngay_ket_thuc" class="form-label fw-bold">
                                        <i class="fas fa-calendar-minus text-danger me-2"></i>
                                        Ngày kết thúc <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" name="ngay_ket_thuc" id="ngay_ket_thuc" 
                                           class="form-control @error('ngay_ket_thuc') is-invalid @enderror" 
                                           value="{{ old('ngay_ket_thuc') }}" required>
                                    @error('ngay_ket_thuc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Application Scope -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-bullseye text-primary me-2"></i>
                                Phạm vi áp dụng
                            </label>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="ap_dung_tat_ca" 
                                       id="ap_dung_tat_ca" value="1" 
                                       {{ old('ap_dung_tat_ca') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="ap_dung_tat_ca">
                                    Áp dụng cho tất cả sản phẩm
                                </label>
                            </div>

                            <div id="specific-application" style="{{ old('ap_dung_tat_ca') ? 'display: none;' : '' }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Sản phẩm cụ thể</label>
                                        <select name="san_pham_ap_dung[]" class="form-select" multiple size="6">
                                            @foreach($products as $product)
                                                <option value="{{ $product->ma_san_pham }}"
                                                    {{ in_array($product->ma_san_pham, old('san_pham_ap_dung', [])) ? 'selected' : '' }}>
                                                    {{ $product->ten_san_pham }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Giữ Ctrl để chọn nhiều sản phẩm</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Danh mục sản phẩm</label>
                                        <select name="danh_muc_ap_dung[]" class="form-select" multiple size="6">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->ma_danh_muc }}"
                                                    {{ in_array($category->ma_danh_muc, old('danh_muc_ap_dung', [])) ? 'selected' : '' }}>
                                                    {{ $category->ten_danh_muc }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Giữ Ctrl để chọn nhiều danh mục</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Image Upload -->
                        <div class="form-group mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image text-primary me-2"></i>
                                Hình ảnh banner khuyến mãi
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
                                    Kích hoạt khuyến mãi
                                </label>
                                <small class="form-text text-muted d-block">
                                    Khuyến mãi sẽ có thể sử dụng khi được kích hoạt và trong thời gian hiệu lực
                                </small>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>
                                Tạo khuyến mãi
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                <i class="fas fa-undo me-1"></i>
                                Reset
                            </button>
                            <a href="{{ route('admin.promotions.index') }}" class="btn btn-outline-secondary">
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
            <!-- Preview Card -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-eye me-2"></i>
                        Xem trước khuyến mãi
                    </h6>
                </div>
                <div class="p-4">
                    <div id="promotion-preview" class="border rounded p-3 bg-light">
                        <h6 id="preview-name" class="text-primary">Tên khuyến mãi sẽ hiển thị ở đây</h6>
                        <p id="preview-description" class="text-muted small">Mô tả khuyến mãi...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="preview-code" class="badge bg-secondary">Mã code</span>
                            <span id="preview-value" class="fw-bold text-success">Giá trị giảm</span>
                        </div>
                        <hr>
                        <div class="small text-muted">
                            <div>Thời gian: <span id="preview-time">--/--/---- đến --/--/----</span></div>
                            <div>Đơn tối thiểu: <span id="preview-min-order">0 VNĐ</span></div>
                            <div>Giới hạn: <span id="preview-limit">Không giới hạn</span></div>
                        </div>
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
                                <i class="fas fa-percentage fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Loại khuyến mãi</h6>
                                <p class="text-muted small mb-0">
                                    <strong>Phần trăm:</strong> Giảm theo % giá trị đơn hàng<br>
                                    <strong>Cố định:</strong> Giảm số tiền cố định<br>
                                    <strong>Sản phẩm:</strong> Giảm giá cho sản phẩm cụ thể
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-code fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Mã khuyến mãi</h6>
                                <p class="text-muted small mb-0">
                                    Khách hàng sẽ nhập mã này để áp dụng khuyến mãi. Để trống để hệ thống tự tạo.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-bullseye fa-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Phạm vi áp dụng</h6>
                                <p class="text-muted small mb-0">
                                    Chọn sản phẩm hoặc danh mục cụ thể để áp dụng khuyến mãi, hoặc áp dụng cho tất cả.
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

#promotion-preview {
    transition: all 0.3s ease;
}

.help-item {
    border-bottom: 1px solid #e9ecef;
    padding-bottom: 1rem;
}

.help-item:last-child {
    border-bottom: none;
    padding-bottom: 0;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Set default start time to now
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    $('#ngay_bat_dau').val(now.toISOString().slice(0, 16));
    
    // Set default end time to 7 days from now
    const endDate = new Date(now.getTime() + 7 * 24 * 60 * 60 * 1000);
    $('#ngay_ket_thuc').val(endDate.toISOString().slice(0, 16));
    
    // Update discount unit based on type
    $('#loai_khuyen_mai').change(function() {
        const type = $(this).val();
        const unit = $('#discount-unit');
        const maxValueField = $('#gia_tri_toi_da').closest('.form-group');
        
        if (type === 'percent') {
            unit.text('%');
            maxValueField.show();
        } else {
            unit.text('VNĐ');
            if (type === 'fixed') {
                maxValueField.hide();
            } else {
                maxValueField.show();
            }
        }
        updatePreview();
    });
    
    // Toggle application scope
    $('#ap_dung_tat_ca').change(function() {
        if (this.checked) {
            $('#specific-application').hide();
        } else {
            $('#specific-application').show();
        }
        updatePreview();
    });
    
    // Image upload preview
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
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Update preview on input changes
    $('input, select, textarea').on('input change', updatePreview);
    
    // Initial preview update
    updatePreview();
    
    // Drag and drop functionality
    $('.upload-area').on('dragover', function(e) {
        e.preventDefault();
        $(this).addClass('dragover');
    });

    $('.upload-area').on('dragleave', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
    });

    $('.upload-area').on('drop', function(e) {
        e.preventDefault();
        $(this).removeClass('dragover');
        
        const files = e.originalEvent.dataTransfer.files;
        if (files.length > 0) {
            $('#hinh_anh')[0].files = files;
            $('#hinh_anh').trigger('change');
        }
    });
    
    // Form validation
    $('#promotion-form').submit(function(e) {
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
        
        // Check date validation
        const startDate = new Date($('#ngay_bat_dau').val());
        const endDate = new Date($('#ngay_ket_thuc').val());
        
        if (endDate <= startDate) {
            isValid = false;
            $('#ngay_ket_thuc').addClass('is-invalid');
            alert('Ngày kết thúc phải sau ngày bắt đầu');
        }
        
        if (!isValid) {
            e.preventDefault();
            alert('Vui lòng kiểm tra lại thông tin form');
        }
    });
});

function generateCode() {
    const length = 8;
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = 'PROMO';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    $('#ma_code').val(result);
    updatePreview();
}

function removeImage() {
    $('#hinh_anh').val('');
    $('#image-preview').hide();
}

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn reset form? Tất cả thông tin sẽ bị mất.')) {
        location.reload();
    }
}

function updatePreview() {
    // Update name
    const name = $('#ten_khuyen_mai').val() || 'Tên khuyến mãi sẽ hiển thị ở đây';
    $('#preview-name').text(name);
    
    // Update description
    const description = $('#mo_ta').val() || 'Mô tả khuyến mãi...';
    $('#preview-description').text(description.substring(0, 100) + (description.length > 100 ? '...' : ''));
    
    // Update code
    const code = $('#ma_code').val() || 'Mã code';
    $('#preview-code').text(code);
    
    // Update value
    const type = $('#loai_khuyen_mai').val();
    const value = $('#gia_tri').val();
    let valueText = 'Giá trị giảm';
    
    if (value && type) {
        if (type === 'percent') {
            valueText = value + '%';
        } else {
            valueText = new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
        }
    }
    $('#preview-value').text(valueText);
    
    // Update time
    const startDate = $('#ngay_bat_dau').val();
    const endDate = $('#ngay_ket_thuc').val();
    let timeText = '--/--/---- đến --/--/----';
    
    if (startDate && endDate) {
        const start = new Date(startDate).toLocaleDateString('vi-VN');
        const end = new Date(endDate).toLocaleDateString('vi-VN');
        timeText = `${start} đến ${end}`;
    }
    $('#preview-time').text(timeText);
    
    // Update min order
    const minOrder = $('#don_hang_toi_thieu').val() || 0;
    const minOrderText = new Intl.NumberFormat('vi-VN').format(minOrder) + ' VNĐ';
    $('#preview-min-order').text(minOrderText);
    
    // Update limit
    const limit = $('#gioi_han_su_dung').val();
    const limitText = limit ? limit + ' lần' : 'Không giới hạn';
    $('#preview-limit').text(limitText);
}
</script>
@endpush