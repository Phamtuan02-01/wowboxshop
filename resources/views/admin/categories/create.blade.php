@extends('layouts.admin')

@section('title', 'Thêm danh mục sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Thêm danh mục sản phẩm</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Main Form -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">Thông tin danh mục</h6>
                </div>
                <div style="padding: 1.5rem;">
                    <form action="{{ route('admin.categories.store') }}" method="POST" id="categoryForm">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="ten_danh_muc" class="form-label required">Tên danh mục</label>
                            <input type="text" 
                                   class="form-control @error('ten_danh_muc') is-invalid @enderror" 
                                   id="ten_danh_muc" 
                                   name="ten_danh_muc" 
                                   value="{{ old('ten_danh_muc') }}" 
                                   placeholder="Nhập tên danh mục..."
                                   maxlength="50"
                                   required>
                            @error('ten_danh_muc')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Tối đa 50 ký tự. <span id="charCount">0/50</span>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="ma_danh_muc_cha" class="form-label">Danh mục cha</label>
                            <select class="form-control @error('ma_danh_muc_cha') is-invalid @enderror" 
                                    id="ma_danh_muc_cha" 
                                    name="ma_danh_muc_cha">
                                <option value="">Chọn danh mục cha (tùy chọn)</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->ma_danh_muc }}" {{ old('ma_danh_muc_cha') == $parent->ma_danh_muc ? 'selected' : '' }}>
                                        {{ $parent->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ma_danh_muc_cha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Để trống nếu đây là danh mục gốc
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu danh mục
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Help Card -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div style="padding: 1.5rem;">
                    <h6><i class="fas fa-info-circle text-info"></i> Lưu ý khi tạo danh mục:</h6>
                    <ul class="mb-3">
                        <li>Tên danh mục phải duy nhất trong hệ thống</li>
                        <li>Tên danh mục không được vượt quá 50 ký tự</li>
                        <li>Có thể tạo danh mục con bằng cách chọn danh mục cha</li>
                        <li>Danh mục gốc không có danh mục cha</li>
                    </ul>

                    <h6><i class="fas fa-lightbulb text-warning"></i> Gợi ý:</h6>
                    <ul class="mb-0">
                        <li>Sử dụng tên ngắn gọn, dễ hiểu</li>
                        <li>Tạo cấu trúc phân cấp hợp lý</li>
                        <li>Ví dụ: Thời trang → Áo → Áo thun</li>
                    </ul>
                </div>
            </div>

            <!-- Preview Card -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-eye text-success me-2"></i>Xem trước
                    </h6>
                </div>
                <div style="padding: 1.5rem;">
                    <div id="categoryPreview">
                        <div class="text-muted text-center">
                            <i class="fas fa-eye fa-2x mb-2"></i>
                            <p>Nhập thông tin để xem trước danh mục</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tenDanhMucInput = document.getElementById('ten_danh_muc');
    const maDanhMucChaSelect = document.getElementById('ma_danh_muc_cha');
    const charCountSpan = document.getElementById('charCount');
    const categoryPreview = document.getElementById('categoryPreview');

    // Character count
    tenDanhMucInput.addEventListener('input', function() {
        const length = this.value.length;
        charCountSpan.textContent = `${length}/50`;
        
        if (length > 45) {
            charCountSpan.classList.add('text-warning');
        } else {
            charCountSpan.classList.remove('text-warning');
        }
        
        updatePreview();
    });

    // Update preview when parent category changes
    maDanhMucChaSelect.addEventListener('change', updatePreview);

    function updatePreview() {
        const tenDanhMuc = tenDanhMucInput.value.trim();
        const parentOption = maDanhMucChaSelect.options[maDanhMucChaSelect.selectedIndex];
        const parentName = parentOption.value ? parentOption.text : null;

        if (!tenDanhMuc) {
            categoryPreview.innerHTML = `
                <div class="text-muted text-center">
                    <i class="fas fa-eye fa-2x mb-2"></i>
                    <p>Nhập thông tin để xem trước danh mục</p>
                </div>
            `;
            return;
        }

        let previewHtml = '<div class="category-preview">';
        
        if (parentName) {
            previewHtml += `
                <div class="parent-category mb-2">
                    <i class="fas fa-folder text-primary"></i>
                    <span class="text-muted">${parentName}</span>
                </div>
                <div class="child-category ml-3">
                    <i class="fas fa-level-up-alt text-muted" style="transform: rotate(90deg);"></i>
                    <strong class="text-dark">${tenDanhMuc}</strong>
                </div>
            `;
        } else {
            previewHtml += `
                <div class="root-category">
                    <i class="fas fa-folder text-primary"></i>
                    <strong class="text-dark">${tenDanhMuc}</strong>
                    <span class="badge badge-primary ml-2">Danh mục gốc</span>
                </div>
            `;
        }
        
        previewHtml += '</div>';
        categoryPreview.innerHTML = previewHtml;
    }

    // Form validation
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const tenDanhMuc = tenDanhMucInput.value.trim();
        
        if (!tenDanhMuc) {
            e.preventDefault();
            alert('Vui lòng nhập tên danh mục');
            tenDanhMucInput.focus();
            return false;
        }
        
        if (tenDanhMuc.length > 50) {
            e.preventDefault();
            alert('Tên danh mục không được vượt quá 50 ký tự');
            tenDanhMucInput.focus();
            return false;
        }
    });
});
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.category-preview {
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    border-left: 4px solid #007bff;
}

.parent-category {
    font-size: 0.9em;
}

.child-category {
    font-size: 1em;
}

.root-category {
    font-size: 1em;
}
</style>
@endsection