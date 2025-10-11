@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Chỉnh sửa người dùng</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                        <li class="breadcrumb-item active">Chỉnh sửa</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.users.show', $user->ma_tai_khoan) }}" class="btn btn-info me-2">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
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
                    <h5 class="card-title mb-0">Chỉnh sửa thông tin</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user->ma_tai_khoan) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Tên đăng nhập</label>
                                    <input type="text" class="form-control @error('ten_dang_nhap') is-invalid @enderror" 
                                           name="ten_dang_nhap" value="{{ old('ten_dang_nhap', $user->ten_dang_nhap) }}" required>
                                    @error('ten_dang_nhap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email', $user->email) }}" required>
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
                                           name="ho_ten" value="{{ old('ho_ten', $user->ho_ten) }}" required>
                                    @error('ho_ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label required">Số điện thoại</label>
                                    <input type="text" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                           name="so_dien_thoai" value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}" required>
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Vai trò</label>
                            <select class="form-select @error('ma_vai_tro') is-invalid @enderror" name="ma_vai_tro" required>
                                <option value="">Chọn vai trò</option>
                                @foreach($vaiTros as $vaiTro)
                                    <option value="{{ $vaiTro->ma_vai_tro }}" 
                                            {{ old('ma_vai_tro', $user->ma_vai_tro) == $vaiTro->ma_vai_tro ? 'selected' : '' }}>
                                        {{ $vaiTro->ten_vai_tro }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ma_vai_tro')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Đổi mật khẩu (tùy chọn)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Mật khẩu mới</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control @error('mat_khau') is-invalid @enderror" 
                                                       name="mat_khau" id="password" placeholder="Để trống nếu không muốn đổi">
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
                                            <label class="form-label">Xác nhận mật khẩu mới</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" 
                                                       name="mat_khau_confirmation" id="password-confirm" placeholder="Xác nhận mật khẩu mới">
                                                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword('password-confirm')">
                                                    <i class="fas fa-eye" id="password-confirm-icon"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                            <a href="{{ route('admin.users.show', $user->ma_tai_khoan) }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- User Preview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin hiện tại</h5>
                </div>
                <div class="card-body text-center">
                    <div class="user-avatar-large mb-3">
                        <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="Avatar" class="avatar-md">
                    </div>
                    <h5>{{ $user->ho_ten }}</h5>
                    <p class="text-muted">{{ $user->vaiTro->ten_vai_tro }}</p>
                    <div class="d-flex justify-content-center">
                        <span class="badge bg-{{ $user->trashed() ? 'danger' : 'success' }}">
                            {{ $user->trashed() ? 'Vô hiệu hóa' : 'Hoạt động' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Hướng dẫn</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Lưu ý khi chỉnh sửa:</h6>
                        <ul class="mb-0">
                            <li>Tên đăng nhập và email phải là duy nhất</li>
                            <li>Để trống mật khẩu nếu không muốn thay đổi</li>
                            <li>Thay đổi vai trò sẽ ảnh hưởng đến quyền truy cập</li>
                            <li>Không thể tự thay đổi vai trò của chính mình</li>
                        </ul>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle"></i> Cảnh báo:</h6>
                        <p class="mb-0">Việc thay đổi vai trò từ Admin xuống Khách hàng sẽ khiến người dùng mất quyền truy cập vào trang quản trị.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thống kê nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Ngày tạo:</span>
                        <strong>{{ $user->ngay_tao->format('d/m/Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Lần cuối cập nhật:</span>
                        <strong>{{ $user->ngay_cap_nhat->format('d/m/Y') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Tổng đơn hàng:</span>
                        <strong>{{ $user->donHangs->count() }}</strong>
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
    
    if (password && password !== confirmPassword) {
        this.classList.add('is-invalid');
        if (!this.nextElementSibling || !this.nextElementSibling.classList.contains('invalid-feedback')) {
            const feedback = document.createElement('div');
            feedback.classList.add('invalid-feedback');
            feedback.textContent = 'Mật khẩu xác nhận không khớp';
            this.parentNode.parentNode.appendChild(feedback);
        }
    } else {
        this.classList.remove('is-invalid');
        const feedback = this.parentNode.parentNode.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
});
</script>
@endsection

@section('styles')
<style>
.avatar-md {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
</style>
@endsection