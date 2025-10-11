@extends('admin.layouts.app')

@section('title', 'Thêm người dùng mới')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Thêm người dùng mới</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                        <li class="breadcrumb-item active">Thêm mới</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin người dùng</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Tên đăng nhập</label>
                                    <input type="text" class="form-control @error('ten_dang_nhap') is-invalid @enderror" 
                                           name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" required>
                                    @error('ten_dang_nhap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Họ tên</label>
                                    <input type="text" class="form-control @error('ho_ten') is-invalid @enderror" 
                                           name="ho_ten" value="{{ old('ho_ten') }}" required>
                                    @error('ho_ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Số điện thoại</label>
                                    <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                           name="so_dien_thoai" value="{{ old('so_dien_thoai') }}" required>
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control @error('mat_khau') is-invalid @enderror" 
                                               name="mat_khau" id="password" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password')">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>
                                    </div>
                                    @error('mat_khau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Xác nhận mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" 
                                               name="mat_khau_confirmation" id="password-confirm" required>
                                        <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password-confirm')">
                                            <i class="fas fa-eye" id="password-confirm-icon"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Vai trò</label>
                            <select class="form-select @error('ma_vai_tro') is-invalid @enderror" name="ma_vai_tro" required>
                                <option value="">Chọn vai trò</option>
                                @foreach($vaiTros as $vaiTro)
                                    <option value="{{ $vaiTro->ma_vai_tro }}" 
                                            {{ old('ma_vai_tro') == $vaiTro->ma_vai_tro ? 'selected' : '' }}>
                                        {{ $vaiTro->ten_vai_tro }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ma_vai_tro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lưu ý khi tạo người dùng:</h6>
                        <ul class="mb-0">
                            <li>Tên đăng nhập phải là duy nhất</li>
                            <li>Email phải là địa chỉ email hợp lệ và duy nhất</li>
                            <li>Mật khẩu phải có ít nhất 6 ký tự</li>
                            <li>Số điện thoại nên có định dạng hợp lệ</li>
                            <li>Vai trò sẽ quyết định quyền truy cập của người dùng</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Vai trò:</h6>
                        <ul class="mb-0">
                            <li><strong>Admin:</strong> Toàn quyền quản trị</li>
                            <li><strong>Khách hàng:</strong> Chỉ được mua hàng</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validate password confirmation
document.querySelector('input[name="mat_khau_confirmation"]').addEventListener('input', function() {
    const password = document.querySelector('input[name="mat_khau"]').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.classList.add('is-invalid');
        this.nextElementSibling.textContent = 'Mật khẩu xác nhận không khớp';
    } else {
        this.classList.remove('is-invalid');
    }
});
</script>
@endsection