@extends('admin.layouts.app')

@section('title', 'Thêm Sản phẩm Mới')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-plus"></i> Thêm Sản phẩm Mới</h1>
        <div class="page-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle"></i> Thông tin sản phẩm</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="ten_san_pham">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ten_san_pham') is-invalid @enderror" 
                                   id="ten_san_pham" name="ten_san_pham" value="{{ old('ten_san_pham') }}" required>
                            @error('ten_san_pham')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ma_danh_muc">Danh mục <span class="text-danger">*</span></label>
                                    <select class="form-control @error('ma_danh_muc') is-invalid @enderror" 
                                            id="ma_danh_muc" name="ma_danh_muc" required>
                                        <option value="">-- Chọn danh mục --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->ma_danh_muc }}" 
                                                    {{ old('ma_danh_muc') == $category->ma_danh_muc ? 'selected' : '' }}>
                                                {{ $category->ten_danh_muc }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ma_danh_muc')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="loai_san_pham">Loại sản phẩm <span class="text-danger">*</span></label>
                                    <select class="form-control @error('loai_san_pham') is-invalid @enderror" 
                                            id="loai_san_pham" name="loai_san_pham" required>
                                        <option value="">-- Chọn loại --</option>
                                        <option value="product" {{ old('loai_san_pham') == 'product' ? 'selected' : '' }}>
                                            Sản phẩm thường
                                        </option>
                                        <option value="ingredient" {{ old('loai_san_pham') == 'ingredient' ? 'selected' : '' }}>
                                            Nguyên liệu
                                        </option>
                                    </select>
                                    @error('loai_san_pham')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mo_ta">Mô tả sản phẩm</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pricing -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gia_display">Giá sản phẩm (nghìn VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('gia') is-invalid @enderror" 
                                               id="gia_display" value="{{ old('gia') ? old('gia') / 1000 : '' }}" 
                                               placeholder="Nhập số nghìn" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">x 1.000 VNĐ</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="gia" id="gia" value="{{ old('gia') }}">
                                    <small class="text-muted">Nhập số ngàn: VD nhập 10 sẽ thành 10.000đ</small>
                                    @error('gia')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Brand & Origin -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="thuong_hieu">Thương hiệu</label>
                                    <input type="text" class="form-control @error('thuong_hieu') is-invalid @enderror" 
                                           id="thuong_hieu" name="thuong_hieu" value="{{ old('thuong_hieu') }}">
                                    @error('thuong_hieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="xuat_xu">Xuất xứ</label>
                                    <input type="text" class="form-control @error('xuat_xu') is-invalid @enderror" 
                                           id="xuat_xu" name="xuat_xu" value="{{ old('xuat_xu') }}">
                                    @error('xuat_xu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <h6 class="mt-4 mb-3"><i class="fas fa-images text-primary"></i> Hình ảnh</h6>
                        
                        <div class="form-group">
                            <label for="hinh_anh">Hình ảnh chính</label>
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center" 
                                 style="cursor: pointer; transition: all 0.3s ease; border: 2px dashed #dee2e6;" 
                                 id="main-upload-area">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">Nhấn để chọn hình ảnh chính</h6>
                                    <p class="text-muted mb-0">hoặc kéo thả file vào đây</p>
                                    <small class="text-muted">Hỗ trợ: JPG, PNG, GIF, WEBP (tối đa 10MB)</small>
                                </div>
                            </div>
                            <input type="file" class="d-none @error('hinh_anh') is-invalid @enderror" 
                                   id="hinh_anh" name="hinh_anh" accept="image/*">
                            @error('hinh_anh')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="main-image-preview" class="mt-3" style="display: none;">
                                <div class="position-relative d-inline-block">
                                    <img id="main-preview-img" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                            style="transform: translate(50%, -50%);" onclick="removeMainImage()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="hinh_anh_phu">Hình ảnh phụ</label>
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center" 
                                 style="cursor: pointer; transition: all 0.3s ease; border: 2px dashed #dee2e6;" 
                                 id="additional-upload-area">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">Nhấn để chọn hình ảnh phụ</h6>
                                    <p class="text-muted mb-0">hoặc kéo thả file vào đây (nhiều file)</p>
                                    <small class="text-muted">Hỗ trợ: JPG, PNG, GIF, WEBP (mỗi file tối đa 10MB)</small>
                                </div>
                            </div>
                            <input type="file" class="d-none @error('hinh_anh_phu') is-invalid @enderror" 
                                   id="hinh_anh_phu" name="hinh_anh_phu[]" accept="image/*" multiple>
                            @error('hinh_anh_phu')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="additional-images-preview" class="mt-3" style="display: none;"></div>
                        </div>

                        <!-- Settings -->
                        <h6 class="mt-4 mb-3"><i class="fas fa-cogs text-secondary"></i> Cài đặt</h6>
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="trang_thai" name="trang_thai" 
                                       value="1" {{ old('trang_thai', true) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="trang_thai">
                                    Kích hoạt sản phẩm
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="la_noi_bat" name="la_noi_bat" 
                                       value="1" {{ old('la_noi_bat') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="la_noi_bat">
                                    Sản phẩm nổi bật
                                </label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu sản phẩm
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Đặt lại
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-light">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-lightbulb"></i> Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <div class="help-section">
                        <h6><i class="fas fa-info-circle text-primary"></i> Loại sản phẩm</h6>
                        <p><strong>Sản phẩm thường:</strong> Các món ăn, đồ uống hoàn chỉnh.</p>
                        <p><strong>Nguyên liệu:</strong> Các thành phần dùng để chế biến món ăn.</p>
                    </div>

                    <div class="help-section">
                        <h6><i class="fas fa-leaf text-success"></i> Thông tin dinh dưỡng</h6>
                        <p>Chỉ hiển thị khi chọn loại "Nguyên liệu". Cung cấp thông tin về calo và giá trị dinh dưỡng.</p>
                    </div>

                    <div class="help-section">
                        <h6><i class="fas fa-tag text-success"></i> Giá cả</h6>
                        <p>Giá khuyến mãi phải nhỏ hơn giá gốc. Nếu không có khuyến mãi, có thể để trống.</p>
                    </div>

                    <div class="help-section">
                        <h6><i class="fas fa-warehouse text-warning"></i> Tồn kho</h6>
                        <p>Cập nhật chính xác số lượng tồn kho để tránh bán quá số lượng có sẵn.</p>
                    </div>

                    <div class="help-section">
                        <h6><i class="fas fa-images text-info"></i> Hình ảnh</h6>
                        <p>Hình ảnh chính sẽ hiển thị làm ảnh đại diện. Hình ảnh phụ hiển thị trong gallery sản phẩm.</p>
                    </div>

                    <div class="help-section">
                        <h6><i class="fas fa-star text-danger"></i> Sản phẩm nổi bật</h6>
                        <p>Đánh dấu sản phẩm nổi bật sẽ hiển thị ưu tiên trên trang chủ.</p>
                    </div>
                </div>
            </div>

            <!-- Preview Images -->
            <div class="card" id="imagePreviewCard" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-eye"></i> Xem trước hình ảnh</h5>
                </div>
                <div class="card-body text-center">
                    <div id="mainImagePreview" style="margin-bottom: 15px;"></div>
                    <div id="additionalImagesPreview"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.help-section {
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #eee;
}

.help-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
}

.help-section h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

.help-section p {
    margin: 0;
    font-size: 14px;
    color: #6c757d;
    line-height: 1.5;
}

.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.form-actions .btn {
    margin-right: 10px;
}

.image-preview {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin-bottom: 10px;
}

#additionalImagesPreview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

#additionalImagesPreview img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    border: 2px solid #ddd;
}

#nutrition-section {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #28a745;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const mainImageInput = document.getElementById('hinh_anh');
    const additionalImagesInput = document.getElementById('hinh_anh_phu');
    const imagePreviewCard = document.getElementById('imagePreviewCard');
    const mainImagePreview = document.getElementById('mainImagePreview');
    const additionalImagesPreview = document.getElementById('additionalImagesPreview');

    // Main image upload area - click to select
    const mainUploadArea = document.getElementById('main-upload-area');
    mainUploadArea.addEventListener('click', function() {
        mainImageInput.click();
    });

    // Main image - drag and drop
    mainUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#0d6efd';
        this.style.backgroundColor = '#e7f1ff';
    });

    mainUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = '';
    });

    mainUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = '';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            mainImageInput.files = files;
            mainImageInput.dispatchEvent(new Event('change'));
        }
    });

    // Main image preview
    const mainImagePreviewDiv = document.getElementById('main-image-preview');
    const mainPreviewImg = document.getElementById('main-preview-img');
    
    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Kích thước file không được vượt quá 10MB');
                this.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                mainPreviewImg.src = e.target.result;
                mainImagePreviewDiv.style.display = 'block';
                mainUploadArea.style.display = 'none';
            };
            reader.readAsDataURL(file);
        }
    });

    // Additional images upload area - click to select
    const additionalUploadArea = document.getElementById('additional-upload-area');
    additionalUploadArea.addEventListener('click', function() {
        additionalImagesInput.click();
    });

    // Additional images - drag and drop
    additionalUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#0d6efd';
        this.style.backgroundColor = '#e7f1ff';
    });

    additionalUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = '';
    });

    additionalUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = '';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            additionalImagesInput.files = files;
            additionalImagesInput.dispatchEvent(new Event('change'));
        }
    });

    // Additional images preview
    const additionalImagesPreviewDiv = document.getElementById('additional-images-preview');
    
    additionalImagesInput.addEventListener('change', function(e) {
        const files = e.target.files;
        additionalImagesPreviewDiv.innerHTML = '';
        
        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert(`File ${file.name} vượt quá 10MB`);
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'position-relative d-inline-block me-2 mb-2';
                    wrapper.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                        <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                style="transform: translate(50%, -50%);" onclick="removeAdditionalImage(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    additionalImagesPreviewDiv.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
            additionalImagesPreviewDiv.style.display = 'block';
            additionalUploadArea.style.display = 'none';
        }
    });

    // Auto-format price input (multiply by 1000)
    const giaDisplayInput = document.getElementById('gia_display');
    const giaInput = document.getElementById('gia');
    
    giaDisplayInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^0-9]/g, '');
        e.target.value = value;
        
        // Convert to actual price (multiply by 1000)
        if (value) {
            giaInput.value = value * 1000;
        } else {
            giaInput.value = '';
        }
    });

    // Form validation before submit
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Ensure gia is set from gia_display
        const displayValue = giaDisplayInput.value.replace(/[^0-9]/g, '');
        if (displayValue) {
            giaInput.value = displayValue * 1000;
        }
    });

    // Remove main image function
    window.removeMainImage = function() {
        mainImageInput.value = '';
        mainImagePreviewDiv.style.display = 'none';
        mainUploadArea.style.display = 'block';
    };

    // Remove additional image function  
    window.removeAdditionalImage = function(index) {
        additionalImagesInput.value = '';
        additionalImagesPreviewDiv.innerHTML = '';
        additionalImagesPreviewDiv.style.display = 'none';
        additionalUploadArea.style.display = 'block';
    };

});
</script>
@endsection