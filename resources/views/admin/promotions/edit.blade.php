@extends('layouts.admin')

@section('title', 'Chỉnh sửa khuyến mãi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Khuyến mãi</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Chỉnh sửa khuyến mãi</h1>
                <p class="text-muted">Cập nhật thông tin chương trình khuyến mãi</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.promotions.show', $promotion) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
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
                        <i class="fas fa-edit me-2"></i>
                        Thông tin khuyến mãi
                    </h6>
                </div>
                
                <div class="p-4">
                    <form action="{{ route('admin.promotions.update', $promotion) }}" method="POST" enctype="multipart/form-data" id="promotion-form">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="form-group mb-4">
                            <label for="ten_khuyen_mai" class="form-label fw-bold">
                                <i class="fas fa-tag text-primary me-2"></i>
                                Tên khuyến mãi <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="ten_khuyen_mai" id="ten_khuyen_mai" 
                                   class="form-control @error('ten_khuyen_mai') is-invalid @enderror" 
                                   value="{{ old('ten_khuyen_mai', $promotion->ten_khuyen_mai) }}" 
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
                                      rows="4" placeholder="Mô tả chi tiết về chương trình khuyến mãi...">{{ old('mo_ta', $promotion->mo_ta) }}</textarea>
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
                                               value="{{ old('ma_code', $promotion->ma_code) }}" 
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
                                        <option value="percent" {{ old('loai_khuyen_mai', $promotion->loai_khuyen_mai) === 'percent' ? 'selected' : '' }}>
                                            Giảm theo phần trăm (%)
                                        </option>
                                        <option value="fixed" {{ old('loai_khuyen_mai', $promotion->loai_khuyen_mai) === 'fixed' ? 'selected' : '' }}>
                                            Giảm số tiền cố định (VNĐ)
                                        </option>
                                        <option value="product_discount" {{ old('loai_khuyen_mai', $promotion->loai_khuyen_mai) === 'product_discount' ? 'selected' : '' }}>
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
                                    <label for="gia_tri_display" class="form-label fw-bold">
                                        <i class="fas fa-money-bill text-warning me-2"></i>
                                        Giá trị giảm <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="gia_tri_display" 
                                               class="form-control @error('gia_tri') is-invalid @enderror" 
                                               value="{{ old('gia_tri', $promotion->loai_khuyen_mai === 'percent' ? $promotion->gia_tri : $promotion->gia_tri / 1000) }}" 
                                               placeholder="0" min="0" step="0.01" required>
                                        <span class="input-group-text" id="discount-unit">
                                            {{ $promotion->loai_khuyen_mai === 'percent' ? '%' : 'nghìn VNĐ' }}
                                        </span>
                                    </div>
                                    <input type="hidden" name="gia_tri" id="gia_tri" value="{{ old('gia_tri', $promotion->gia_tri) }}">
                                    <small class="form-text text-muted" id="gia_tri_helper" style="{{ $promotion->loai_khuyen_mai === 'percent' ? 'display: none;' : '' }}">
                                        Nhập số nghìn (VD: nhập 10 = 10.000 VNĐ)
                                    </small>
                                    @error('gia_tri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Max Discount Value -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="gia_tri_toi_da_display" class="form-label fw-bold">
                                        <i class="fas fa-chart-line text-warning me-2"></i>
                                        Giá trị giảm tối đa
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="gia_tri_toi_da_display" 
                                               class="form-control @error('gia_tri_toi_da') is-invalid @enderror" 
                                               value="{{ old('gia_tri_toi_da', $promotion->gia_tri_toi_da ? $promotion->gia_tri_toi_da / 1000 : '') }}" 
                                               placeholder="0" min="0" step="0.01">
                                        <span class="input-group-text">nghìn VNĐ</span>
                                    </div>
                                    <input type="hidden" name="gia_tri_toi_da" id="gia_tri_toi_da" value="{{ old('gia_tri_toi_da', $promotion->gia_tri_toi_da) }}">
                                    <small class="form-text text-muted">
                                        Chỉ áp dụng cho loại giảm theo phần trăm. Nhập số nghìn (VD: 50 = 50.000 VNĐ)
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
                                    <label for="don_hang_toi_thieu_display" class="form-label fw-bold">
                                        <i class="fas fa-shopping-cart text-secondary me-2"></i>
                                        Đơn hàng tối thiểu
                                    </label>
                                    <div class="input-group">
                                        <input type="number" id="don_hang_toi_thieu_display" 
                                               class="form-control @error('don_hang_toi_thieu') is-invalid @enderror" 
                                               value="{{ old('don_hang_toi_thieu', $promotion->don_hang_toi_thieu / 1000) }}" 
                                               placeholder="0" min="0" step="0.01">
                                        <span class="input-group-text">nghìn VNĐ</span>
                                    </div>
                                    <input type="hidden" name="don_hang_toi_thieu" id="don_hang_toi_thieu" value="{{ old('don_hang_toi_thieu', $promotion->don_hang_toi_thieu) }}">
                                    <small class="form-text text-muted">
                                        Nhập số nghìn (VD: 100 = 100.000 VNĐ)
                                    </small>
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
                                           value="{{ old('gioi_han_su_dung', $promotion->gioi_han_su_dung) }}" 
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
                                   value="{{ old('gioi_han_moi_khach', $promotion->gioi_han_moi_khach) }}" 
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
                                           value="{{ old('ngay_bat_dau', $promotion->ngay_bat_dau->format('Y-m-d\TH:i')) }}" required>
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
                                           value="{{ old('ngay_ket_thuc', $promotion->ngay_ket_thuc->format('Y-m-d\TH:i')) }}" required>
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
                                       {{ old('ap_dung_tat_ca', $promotion->ap_dung_tat_ca) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="ap_dung_tat_ca">
                                    Áp dụng cho tất cả sản phẩm
                                </label>
                            </div>

                            <div id="specific-application" style="{{ old('ap_dung_tat_ca', $promotion->ap_dung_tat_ca) ? 'display: none;' : '' }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Sản phẩm cụ thể</label>
                                        <select name="san_pham_ap_dung[]" class="form-select" multiple size="12">
                                            @foreach($products as $product)
                                                <option value="{{ $product->ma_san_pham }}"
                                                    {{ in_array($product->ma_san_pham, old('san_pham_ap_dung', $promotion->san_pham_ap_dung ?? [])) ? 'selected' : '' }}>
                                                    {{ $product->ten_san_pham }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Giữ Ctrl để chọn nhiều sản phẩm</small>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label class="form-label">Danh mục sản phẩm</label>
                                        <select name="danh_muc_ap_dung[]" class="form-select" multiple size="12">
                                            @foreach($categories as $category)
                                                <option value="{{ $category->ma_danh_muc }}"
                                                    {{ in_array($category->ma_danh_muc, old('danh_muc_ap_dung', $promotion->danh_muc_ap_dung ?? [])) ? 'selected' : '' }}>
                                                    {{ $category->ten_danh_muc }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Giữ Ctrl để chọn nhiều danh mục</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Status Toggle -->
                        <div class="form-group mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="trang_thai" id="trang_thai" 
                                       value="1" {{ old('trang_thai', $promotion->trang_thai) ? 'checked' : '' }}>
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
                                Cập nhật khuyến mãi
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
            <!-- Current Status -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin hiện tại
                    </h6>
                </div>
                <div class="p-4">
                    <div class="info-list">
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <strong>ID:</strong> 
                            <span class="badge bg-primary">{{ $promotion->ma_khuyen_mai }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <strong>Trạng thái:</strong> 
                            @php
                                $statusClass = 'secondary';
                                $statusText = $promotion->trang_thai_text;
                                if ($promotion->trang_thai_text === 'Đang hoạt động') $statusClass = 'success';
                                elseif ($promotion->trang_thai_text === 'Đã kết thúc') $statusClass = 'danger';
                                elseif ($promotion->trang_thai_text === 'Chưa bắt đầu') $statusClass = 'warning';
                                elseif ($promotion->trang_thai_text === 'Đã tắt') $statusClass = 'secondary';
                            @endphp
                            <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2 border-bottom">
                            <strong>Đã sử dụng:</strong> 
                            <span class="text-muted">{{ $promotion->so_luong_su_dung }} lần</span>
                        </div>
                        <div class="info-item d-flex justify-content-between py-2">
                            <strong>Ngày tạo:</strong> 
                            <span class="text-muted">{{ $promotion->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Usage Preview -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống kê sử dụng
                    </h6>
                </div>
                <div class="p-4 text-center">
                    <div class="row">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ $promotion->so_luong_su_dung }}</h4>
                                <small class="text-muted">Lần sử dụng</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">
                                {{ $promotion->gioi_han_su_dung ? max(0, $promotion->gioi_han_su_dung - $promotion->so_luong_su_dung) : '∞' }}
                            </h4>
                            <small class="text-muted">Còn lại</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Help Section -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-question-circle me-2"></i>
                        Lưu ý khi chỉnh sửa
                    </h6>
                </div>
                <div class="p-4">
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Thay đổi thời gian</h6>
                                <p class="text-muted small mb-0">
                                    Việc thay đổi thời gian hiệu lực có thể ảnh hưởng đến khách hàng đang sử dụng.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item mb-4">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-users fa-2x text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Giới hạn sử dụng</h6>
                                <p class="text-muted small mb-0">
                                    Giảm giới hạn sử dụng có thể khiến một số khách hàng không thể sử dụng.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="help-item">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-money-bill fa-2x text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-2">Giá trị giảm</h6>
                                <p class="text-muted small mb-0">
                                    Thay đổi giá trị giảm sẽ áp dụng cho các đơn hàng mới.
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
    // Update discount unit based on type
    $('#loai_khuyen_mai').change(function() {
        const type = $(this).val();
        const unit = $('#discount-unit');
        const maxValueField = $('#gia_tri_toi_da_display').closest('.form-group');
        const helperText = $('#gia_tri_helper');
        
        if (type === 'percent') {
            unit.text('%');
            helperText.hide();
            maxValueField.show();
        } else {
            unit.text('nghìn VNĐ');
            helperText.show();
            if (type === 'fixed') {
                maxValueField.hide();
            } else {
                maxValueField.show();
            }
        }
    });
    
    // Auto convert display values to actual values (multiply by 1000 for non-percent)
    function syncDisplayToActual() {
        const type = $('#loai_khuyen_mai').val();
        
        if (type !== 'percent') {
            // Convert gia_tri: display value * 1000 = actual value
            const giaTriDisplay = parseFloat($('#gia_tri_display').val()) || 0;
            $('#gia_tri').val(giaTriDisplay * 1000);
        } else {
            // For percent, keep the same value
            $('#gia_tri').val($('#gia_tri_display').val());
        }
        
        // Always convert these values (they're always in VND)
        const giaTriToiDaDisplay = parseFloat($('#gia_tri_toi_da_display').val()) || 0;
        $('#gia_tri_toi_da').val(giaTriToiDaDisplay * 1000);
        
        const donHangDisplay = parseFloat($('#don_hang_toi_thieu_display').val()) || 0;
        $('#don_hang_toi_thieu').val(donHangDisplay * 1000);
    }
    
    // Sync on input change
    $('#gia_tri_display, #gia_tri_toi_da_display, #don_hang_toi_thieu_display').on('input', syncDisplayToActual);
    $('#loai_khuyen_mai').on('change', syncDisplayToActual);
    
    // Sync before form submit
    $('#promotion-form').on('submit', function(e) {
        syncDisplayToActual();
    });
    
    // Toggle application scope
    $('#ap_dung_tat_ca').change(function() {
        if (this.checked) {
            $('#specific-application').hide();
        } else {
            $('#specific-application').show();
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
    
    // Initialize max value field visibility
    $('#loai_khuyen_mai').trigger('change');
});

function generateCode() {
    const length = 8;
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = 'PROMO';
    for (let i = 0; i < length; i++) {
        result += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    $('#ma_code').val(result);
}

function resetForm() {
    if (confirm('Bạn có chắc chắn muốn reset form? Tất cả thay đổi sẽ bị mất.')) {
        location.reload();
    }
}
</script>
@endpush