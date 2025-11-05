@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Sản phẩm')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-edit"></i> Chỉnh sửa Sản phẩm</h1>
        <div class="page-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
            <a href="{{ route('admin.products.show', $product->ma_san_pham) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Xem chi tiết
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
                    <form method="POST" action="{{ route('admin.products.update', $product->ma_san_pham) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="ten_san_pham">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ten_san_pham') is-invalid @enderror" 
                                   id="ten_san_pham" name="ten_san_pham" 
                                   value="{{ old('ten_san_pham', $product->ten_san_pham) }}" required>
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
                                                    {{ old('ma_danh_muc', $product->ma_danh_muc) == $category->ma_danh_muc ? 'selected' : '' }}>
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
                                        <option value="product" {{ old('loai_san_pham', $product->loai_san_pham) == 'product' ? 'selected' : '' }}>
                                            Sản phẩm thường
                                        </option>
                                        <option value="ingredient" {{ old('loai_san_pham', $product->loai_san_pham) == 'ingredient' ? 'selected' : '' }}>
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
                                      id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta', $product->mo_ta) }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gia_display">Giá sản phẩm (nghìn VNĐ) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control @error('gia') is-invalid @enderror" 
                                               id="gia_display" 
                                               value="{{ old('gia') ? old('gia') / 1000 : $product->gia / 1000 }}" 
                                               placeholder="Nhập số nghìn" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">x 1.000 VNĐ</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="gia" id="gia" value="{{ old('gia', $product->gia) }}">
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
                                           id="thuong_hieu" name="thuong_hieu" 
                                           value="{{ old('thuong_hieu', $product->thuong_hieu) }}">
                                    @error('thuong_hieu')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="xuat_xu">Xuất xứ</label>
                                    <input type="text" class="form-control @error('xuat_xu') is-invalid @enderror" 
                                           id="xuat_xu" name="xuat_xu" 
                                           value="{{ old('xuat_xu', $product->xuat_xu) }}">
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
                            @if($product->hinh_anh)
                                <div class="current-image mb-3">
                                    <p class="mb-2">Hình ảnh hiện tại:</p>
                                    <img src="{{ asset('images/products/' . $product->hinh_anh) }}" 
                                         alt="{{ $product->ten_san_pham }}" 
                                         class="img-thumbnail"
                                         style="max-height: 150px;">
                                </div>
                            @endif
                            
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center" 
                                 style="cursor: pointer; transition: all 0.3s ease; border: 2px dashed #dee2e6;" 
                                 id="main-upload-area">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">Nhấn để {{ $product->hinh_anh ? 'thay đổi' : 'chọn' }} hình ảnh chính</h6>
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
                            @if($product->hinh_anh_phu)
                                @php
                                    $additionalImages = json_decode($product->hinh_anh_phu, true);
                                @endphp
                                @if(is_array($additionalImages) && count($additionalImages) > 0)
                                    <div class="current-additional-images mb-3">
                                        <p class="mb-2">Hình ảnh phụ hiện tại:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($additionalImages as $image)
                                                <img src="{{ asset('images/products/' . $image) }}" 
                                                     alt="Additional image" 
                                                     class="img-thumbnail"
                                                     style="max-height: 100px; margin-right: 8px; margin-bottom: 8px;">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endif
                            
                            <div class="upload-area border-2 border-dashed rounded p-4 text-center" 
                                 style="cursor: pointer; transition: all 0.3s ease; border: 2px dashed #dee2e6;" 
                                 id="additional-upload-area">
                                <div class="upload-content">
                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                    <h6 class="mb-2">Nhấn để {{ $product->hinh_anh_phu ? 'thay đổi' : 'chọn' }} hình ảnh phụ</h6>
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
                                       value="1" {{ old('trang_thai', $product->trang_thai) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="trang_thai">
                                    Kích hoạt sản phẩm
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="la_noi_bat" name="la_noi_bat" 
                                       value="1" {{ old('la_noi_bat', $product->la_noi_bat) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="la_noi_bat">
                                    Sản phẩm nổi bật
                                </label>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật sản phẩm
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
                    <h5><i class="fas fa-info"></i> Thông tin sản phẩm</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td><strong>Mã sản phẩm:</strong></td>
                            <td>{{ $product->ma_san_pham }}</td>
                        </tr>
                        <tr>
                            <td><strong>Ngày tạo:</strong></td>
                            <td>{{ $product->ngay_tao->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật gần nhất:</strong></td>
                            <td>{{ $product->ngay_cap_nhat->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>
                                <span class="badge {{ $product->trang_thai ? 'badge-success' : 'badge-danger' }}">
                                    {{ $product->trang_thai ? 'Hoạt động' : 'Tạm ngưng' }}
                                </span>
                            </td>
                        </tr>
                        @if($product->luot_xem)
                        <tr>
                            <td><strong>Lượt xem:</strong></td>
                            <td>{{ number_format($product->luot_xem) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Preview Image -->
            <div class="card" id="imagePreviewCard" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-eye"></i> Xem trước hình ảnh mới</h5>
                </div>
                <div class="card-body text-center">
                    <img id="imagePreview" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 8px;">
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-actions {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.form-actions .btn {
    margin-right: 10px;
}

.current-image img {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.current-additional-images {
    background-color: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border: 1px solid #dee2e6;
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

    // Main image preview
    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (mainImagePreview) {
                    mainImagePreview.innerHTML = `<img src="${e.target.result}" alt="Main Preview" class="image-preview">`;
                } else {
                    // Fallback for simple preview
                    const previewImg = document.getElementById('imagePreview');
                    if (previewImg) {
                        previewImg.src = e.target.result;
                        imagePreviewCard.style.display = 'block';
                    }
                }
            };
            reader.readAsDataURL(file);
        }
    });

    // Additional images preview
    if (additionalImagesInput) {
        additionalImagesInput.addEventListener('change', function(e) {
            const files = e.target.files;
            if (additionalImagesPreview) {
                additionalImagesPreview.innerHTML = '';
                
                if (files.length > 0) {
                    Array.from(files).forEach(file => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.alt = 'Additional Preview';
                            additionalImagesPreview.appendChild(img);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            }
        });
    }

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

    // Drag and drop for main image
    const mainUploadArea = document.getElementById('main-upload-area');
    const mainImageInput = document.getElementById('hinh_anh');
    const mainImagePreviewDiv = document.getElementById('main-image-preview');
    const mainPreviewImg = document.getElementById('main-preview-img');
    
    mainUploadArea.addEventListener('click', function() {
        mainImageInput.click();
    });

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

    mainImageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
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

    // Drag and drop for additional images
    const additionalUploadArea = document.getElementById('additional-upload-area');
    const additionalImagesInput = document.getElementById('hinh_anh_phu');
    const additionalImagesPreviewDiv = document.getElementById('additional-images-preview');
    
    additionalUploadArea.addEventListener('click', function() {
        additionalImagesInput.click();
    });

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

    additionalImagesInput.addEventListener('change', function(e) {
        const files = e.target.files;
        additionalImagesPreviewDiv.innerHTML = '';
        
        if (files.length > 0) {
            Array.from(files).forEach((file, index) => {
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

    // Remove functions
    window.removeMainImage = function() {
        mainImageInput.value = '';
        mainImagePreviewDiv.style.display = 'none';
        mainUploadArea.style.display = 'block';
    };

    window.removeAdditionalImage = function(index) {
        additionalImagesInput.value = '';
        additionalImagesPreviewDiv.innerHTML = '';
        additionalImagesPreviewDiv.style.display = 'none';
        additionalUploadArea.style.display = 'block';
    };

    // Form submission - ensure gia is set
    const form = document.querySelector('form');
    form.addEventListener('submit', function() {
        const displayValue = giaDisplayInput.value.replace(/[^0-9]/g, '');
        if (displayValue) {
            giaInput.value = displayValue * 1000;
        }
    });
});
</script>
@endsection