@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<style>
    .profile-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .profile-header {
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        color: white;
        padding: 2rem;
        border-radius: 20px;
        box-shadow: 0 8px 32px rgba(46, 125, 50, 0.3);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #2E7D32;
        font-weight: bold;
        box-shadow: 0 8px 20px rgba(255, 225, 53, 0.4);
        border: 5px solid white;
    }

    .profile-info h1 {
        margin: 0 0 0.5rem 0;
        font-size: 2rem;
        font-weight: 600;
    }

    .profile-info p {
        margin: 0;
        opacity: 0.95;
        font-size: 1.1rem;
    }

    .profile-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        border-left: 5px solid #2E7D32;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2E7D32;
        margin: 0.5rem 0;
    }

    .stat-label {
        color: #666;
        font-size: 0.95rem;
        margin: 0;
    }

    .profile-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    .profile-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    }

    .profile-card h2 {
        color: #2E7D32;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 3px solid #FFE135;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .form-control:focus {
        outline: none;
        border-color: #2E7D32;
        background: white;
        box-shadow: 0 0 0 3px rgba(46, 125, 50, 0.1);
    }

    .form-control:disabled {
        background: #e9ecef;
        cursor: not-allowed;
    }

    .btn-primary {
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        color: white;
        border: none;
        padding: 1rem 2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(46, 125, 50, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(46, 125, 50, 0.4);
    }

    .btn-secondary {
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        color: #2E7D32;
        border: none;
        padding: 0.8rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 0.5rem;
    }

    .btn-secondary:hover {
        background: linear-gradient(135deg, #FDD835, #FFE135);
        transform: translateY(-2px);
    }

    .info-item {
        display: flex;
        padding: 1rem;
        border-bottom: 1px solid #f0f0f0;
        align-items: center;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #2E7D32, #388E3C);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .info-content {
        flex: 1;
    }

    .info-label {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 0.2rem;
    }

    .info-value {
        font-size: 1rem;
        color: #333;
        font-weight: 500;
    }

    .badge-role {
        display: inline-block;
        padding: 0.4rem 1rem;
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        color: #2E7D32;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: linear-gradient(135deg, #d4edda, #c3e6cb);
        color: #155724;
        border-left: 4px solid #28a745;
    }

    .alert-danger {
        background: linear-gradient(135deg, #f8d7da, #f5c6cb);
        color: #721c24;
        border-left: 4px solid #dc3545;
    }

    @media (max-width: 768px) {
        .profile-content {
            grid-template-columns: 1fr;
        }

        .profile-header {
            flex-direction: column;
            text-align: center;
        }

        .profile-stats {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-avatar">
            {{ strtoupper(substr($user->ho_ten, 0, 1)) }}
        </div>
        <div class="profile-info">
            <h1>{{ $user->ho_ten }}</h1>
            <p><i class="fas fa-at"></i> {{ $user->ten_dang_nhap }}</p>
            @if($user->vaiTro)
            <span class="badge-role">
                <i class="fas fa-shield-alt"></i> {{ $user->vaiTro->ten_vai_tro }}
            </span>
            @endif
        </div>
    </div>

    <!-- Statistics -->
    <div class="profile-stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="stat-number">{{ $tongDonHang }}</div>
            <p class="stat-label">Tổng đơn hàng</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $donHangChoXacNhan }}</div>
            <p class="stat-label">Chờ xác nhận</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <div class="stat-number">{{ $donHangDangGiao }}</div>
            <p class="stat-label">Đang giao hàng</p>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $donHangHoanThanh }}</div>
            <p class="stat-label">Đã hoàn thành</p>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Edit Profile Form -->
        <div>
            <div class="profile-card">
                <h2>
                    <i class="fas fa-user-edit"></i>
                    Chỉnh sửa thông tin
                </h2>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Họ và tên <span style="color: red;">*</span></label>
                        <input type="text" name="ho_ten" class="form-control @error('ho_ten') is-invalid @enderror" 
                               value="{{ old('ho_ten', $user->ho_ten) }}" required>
                        @error('ho_ten')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tên đăng nhập <span style="color: red;">*</span></label>
                        <input type="text" name="ten_dang_nhap" class="form-control @error('ten_dang_nhap') is-invalid @enderror" 
                               value="{{ old('ten_dang_nhap', $user->ten_dang_nhap) }}" required>
                        @error('ten_dang_nhap')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span style="color: red;">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="so_dien_thoai" class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                               value="{{ old('so_dien_thoai', $user->so_dien_thoai) }}">
                        @error('so_dien_thoai')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Địa chỉ</label>
                        <textarea name="dia_chi" class="form-control @error('dia_chi') is-invalid @enderror" 
                                  rows="3">{{ old('dia_chi', $user->dia_chi) }}</textarea>
                        @error('dia_chi')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Cập nhật thông tin
                    </button>
                </form>
            </div>

            <!-- Change Password Form -->
            <div class="profile-card" style="margin-top: 2rem;">
                <h2>
                    <i class="fas fa-key"></i>
                    Đổi mật khẩu
                </h2>

                <form action="{{ route('profile.update-password') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Mật khẩu hiện tại <span style="color: red;">*</span></label>
                        <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mật khẩu mới <span style="color: red;">*</span></label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" required>
                        @error('new_password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Xác nhận mật khẩu mới <span style="color: red;">*</span></label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-lock"></i>
                        Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>

        <!-- Account Info -->
        <div class="profile-card">
            <h2>
                <i class="fas fa-info-circle"></i>
                Thông tin tài khoản
            </h2>

            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Vai trò</div>
                    <div class="info-value">{{ $user->vaiTro ? $user->vaiTro->ten_vai_tro : 'Không xác định' }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Ngày tạo tài khoản</div>
                    <div class="info-value">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'Không rõ' }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Cập nhật lần cuối</div>
                    <div class="info-value">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'Không rõ' }}</div>
                </div>
            </div>

            <div class="info-item">
                <div class="info-icon">
                    <i class="fas fa-toggle-on"></i>
                </div>
                <div class="info-content">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">
                        <span style="color: #28a745; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> Đang hoạt động
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
