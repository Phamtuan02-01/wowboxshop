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
                        
                        <div class="form-group">
                            <label for="ten_san_pham">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('ten_san_pham') is-invalid @enderror" 
                                   id="ten_san_pham" name="ten_san_pham" value="{{ old('ten_san_pham') }}" required>
                            @error('ten_san_pham')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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

                        <div class="form-group">
                            <label for="mo_ta">Mô tả sản phẩm</label>
                            <textarea class="form-control @error('mo_ta') is-invalid @enderror" 
                                      id="mo_ta" name="mo_ta" rows="4">{{ old('mo_ta') }}</textarea>
                            @error('mo_ta')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gia">Giá sản phẩm <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('gia') is-invalid @enderror" 
                                               id="gia" name="gia" value="{{ old('gia') }}" 
                                               step="1000" min="0" required>
                                        <div class="input-group-append">
                                            <span class="input-group-text">đ</span>
                                        </div>
                                        @error('gia')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gia_khuyen_mai">Giá khuyến mãi</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('gia_khuyen_mai') is-invalid @enderror" 
                                               id="gia_khuyen_mai" name="gia_khuyen_mai" value="{{ old('gia_khuyen_mai') }}" 
                                               step="1000" min="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text">đ</span>
                                        </div>
                                        @error('gia_khuyen_mai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="so_luong_ton_kho">Số lượng tồn kho <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('so_luong_ton_kho') is-invalid @enderror" 
                                   id="so_luong_ton_kho" name="so_luong_ton_kho" value="{{ old('so_luong_ton_kho') }}" 
                                   min="0" required>
                            @error('so_luong_ton_kho')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

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

                        <div class="form-group">
                            <label for="hinh_anh">Hình ảnh sản phẩm</label>
                            <input type="file" class="form-control-file @error('hinh_anh') is-invalid @enderror" 
                                   id="hinh_anh" name="hinh_anh" accept="image/*">
                            @error('hinh_anh')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Chọn hình ảnh có định dạng: JPG, PNG, GIF. Kích thước tối đa: 2MB.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="trang_thai" name="trang_thai" 
                                       {{ old('trang_thai') ? 'checked' : '' }}>
                                <label class="custom-control-label" for="trang_thai">
                                    Kích hoạt sản phẩm
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="la_noi_bat" name="la_noi_bat" 
                                       {{ old('la_noi_bat') ? 'checked' : '' }}>
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
                        <h6><i class="fas fa-info-circle text-primary"></i> Thông tin cơ bản</h6>
                        <p>Nhập đầy đủ thông tin sản phẩm để khách hàng dễ dàng tìm hiểu và quyết định mua hàng.</p>
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
                        <h6><i class="fas fa-image text-info"></i> Hình ảnh</h6>
                        <p>Sử dụng hình ảnh chất lượng cao, rõ nét để thu hút khách hàng.</p>
                    </div>

                    <div class="help-section">
                        <h6><i class="fas fa-star text-danger"></i> Sản phẩm nổi bật</h6>
                        <p>Đánh dấu sản phẩm nổi bật sẽ hiển thị ưu tiên trên trang chủ.</p>
                    </div>
                </div>
            </div>

            <!-- Preview Image -->
            <div class="card" id="imagePreviewCard" style="display: none;">
                <div class="card-header">
                    <h5><i class="fas fa-eye"></i> Xem trước hình ảnh</h5>
                </div>
                <div class="card-body text-center">
                    <img id="imagePreview" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 8px;">
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

#imagePreview {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const imageInput = document.getElementById('hinh_anh');
    const imagePreview = document.getElementById('imagePreview');
    const imagePreviewCard = document.getElementById('imagePreviewCard');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewCard.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreviewCard.style.display = 'none';
        }
    });

    // Price validation
    const giaInput = document.getElementById('gia');
    const giaKhuyenMaiInput = document.getElementById('gia_khuyen_mai');

    function validatePrices() {
        const gia = parseFloat(giaInput.value) || 0;
        const giaKhuyenMai = parseFloat(giaKhuyenMaiInput.value) || 0;

        if (giaKhuyenMai > 0 && giaKhuyenMai >= gia) {
            giaKhuyenMaiInput.setCustomValidity('Giá khuyến mãi phải nhỏ hơn giá gốc');
        } else {
            giaKhuyenMaiInput.setCustomValidity('');
        }
    }

    giaInput.addEventListener('input', validatePrices);
    giaKhuyenMaiInput.addEventListener('input', validatePrices);

    // Format currency inputs
    function formatCurrency(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value) {
                e.target.value = parseInt(value);
            }
        });
    }

    formatCurrency(giaInput);
    formatCurrency(giaKhuyenMaiInput);
});
</script>
@endsection