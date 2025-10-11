@extends('layouts.admin')

@section('title', 'Chỉnh sửa danh mục: ' . $category->ten_danh_muc)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Chỉnh sửa danh mục: {{ $category->ten_danh_muc }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
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
                    <form action="{{ route('admin.categories.update', $category) }}" method="POST" id="categoryForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="ten_danh_muc" class="form-label required">Tên danh mục</label>
                            <input type="text" 
                                   class="form-control @error('ten_danh_muc') is-invalid @enderror" 
                                   id="ten_danh_muc" 
                                   name="ten_danh_muc" 
                                   value="{{ old('ten_danh_muc', $category->ten_danh_muc) }}" 
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
                                    <option value="{{ $parent->ma_danh_muc }}" 
                                            {{ old('ma_danh_muc_cha', $category->ma_danh_muc_cha) == $parent->ma_danh_muc ? 'selected' : '' }}>
                                        {{ $parent->ten_danh_muc }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ma_danh_muc_cha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Để trống nếu đây là danh mục gốc. Không thể chọn chính nó hoặc danh mục con làm danh mục cha.
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật danh mục
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
            <!-- Current Info Card -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-info-circle text-info me-2"></i>Thông tin hiện tại
                    </h6>
                </div>
                <div style="padding: 1.5rem;">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $category->ma_danh_muc }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tên:</strong></td>
                            <td>{{ $category->ten_danh_muc }}</td>
                        </tr>
                        <tr>
                            <td><strong>Danh mục cha:</strong></td>
                            <td>
                                @if($category->danhMucCha)
                                    <span class="badge bg-secondary">{{ $category->danhMucCha->ten_danh_muc }}</span>
                                @else
                                    <span class="text-muted">Danh mục gốc</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Danh mục con:</strong></td>
                            <td><span class="badge bg-info">{{ $category->danhMucCons()->count() }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Sản phẩm:</strong></td>
                            <td><span class="badge bg-success">{{ $category->sanPhams()->count() }}</span></td>
                        </tr>
                        <tr>
                            <td><strong>Ngày tạo:</strong></td>
                            <td>{{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : 'Không có' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Cập nhật:</strong></td>
                            <td>{{ $category->updated_at ? $category->updated_at->format('d/m/Y H:i') : 'Không có' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Warning Card -->
            @if($category->danhMucCons()->count() > 0 || $category->sanPhams()->count() > 0)
            <div class="table-container mb-4" style="border-left: 4px solid #ffc107;">
                <div class="table-header" style="background-color: #fff3cd;">
                    <h6 class="section-title mb-0" style="color: #856404;">
                        <i class="fas fa-exclamation-triangle me-2"></i>Cảnh báo
                    </h6>
                </div>
                <div style="padding: 1.5rem; background-color: #fff3cd;">
                    <p class="mb-2" style="color: #856404;"><strong>Lưu ý khi thay đổi danh mục này:</strong></p>
                    <ul class="mb-0" style="color: #856404;">
                        @if($category->danhMucCons()->count() > 0)
                        <li>Có {{ $category->danhMucCons()->count() }} danh mục con</li>
                        @endif
                        @if($category->sanPhams()->count() > 0)
                        <li>Có {{ $category->sanPhams()->count() }} sản phẩm</li>
                        @endif
                    </ul>
                    <small class="text-muted">Thay đổi có thể ảnh hưởng đến cấu trúc danh mục và sản phẩm.</small>
                </div>
            </div>
            @endif

            <!-- Help Card -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-question-circle text-success me-2"></i>Hướng dẫn
                    </h6>
                </div>
                <div style="padding: 1.5rem;">
                    <h6><i class="fas fa-info-circle text-info"></i> Lưu ý khi chỉnh sửa:</h6>
                    <ul class="mb-3">
                        <li>Tên danh mục phải duy nhất</li>
                        <li>Không thể chọn chính nó làm danh mục cha</li>
                        <li>Không thể chọn danh mục con làm danh mục cha</li>
                        <li>Thay đổi có thể ảnh hưởng đến cấu trúc</li>
                    </ul>

                    <h6><i class="fas fa-lightbulb text-warning"></i> Gợi ý:</h6>
                    <ul class="mb-0">
                        <li>Kiểm tra kỹ trước khi thay đổi</li>
                        <li>Đảm bảo cấu trúc hợp lý</li>
                        <li>Tham khảo danh mục hiện có</li>
                    </ul>
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
    const charCountSpan = document.getElementById('charCount');

    // Initialize character count
    updateCharCount();

    // Character count
    tenDanhMucInput.addEventListener('input', updateCharCount);

    function updateCharCount() {
        const length = tenDanhMucInput.value.length;
        charCountSpan.textContent = `${length}/50`;
        
        if (length > 45) {
            charCountSpan.classList.add('text-warning');
        } else {
            charCountSpan.classList.remove('text-warning');
        }
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

        // Confirm update if category has children or products
        @if($category->danhMucCons()->count() > 0 || $category->sanPhams()->count() > 0)
        if (!confirm('Danh mục này có liên kết với danh mục con hoặc sản phẩm. Bạn có chắc chắn muốn cập nhật?')) {
            e.preventDefault();
            return false;
        }
        @endif
    });
});
</script>

<style>
.required::after {
    content: " *";
    color: red;
}
</style>
@endsection