@extends('admin.layouts.app')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Chi tiết người dùng</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Người dùng</a></li>
                        <li class="breadcrumb-item active">{{ $user->ho_ten }}</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.users.edit', $user->ma_tai_khoan) }}" class="btn btn-primary me-2">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- User Info -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="user-avatar-large mb-3">
                        <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="Avatar" class="avatar-lg">
                    </div>
                    <h4>{{ $user->ho_ten }}</h4>
                    <p class="text-muted">{{ $user->vaiTro->ten_vai_tro }}</p>
                    
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span class="badge bg-{{ $user->trashed() ? 'danger' : 'success' }} fs-6">
                            {{ $user->trashed() ? 'Vô hiệu hóa' : 'Hoạt động' }}
                        </span>
                    </div>

                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <div class="fw-bold fs-4 text-primary">{{ $stats['total_orders'] }}</div>
                                <div class="text-muted small">Đơn hàng</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="fw-bold fs-4 text-success">{{ number_format($stats['total_spent']) }}đ</div>
                            <div class="text-muted small">Tổng chi tiêu</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin liên hệ</h5>
                </div>
                <div class="card-body">
                    <div class="info-item">
                        <i class="fas fa-user text-muted me-2"></i>
                        <strong>Tên đăng nhập:</strong> {{ $user->ten_dang_nhap }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope text-muted me-2"></i>
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone text-muted me-2"></i>
                        <strong>Số điện thoại:</strong> {{ $user->so_dien_thoai }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-calendar text-muted me-2"></i>
                        <strong>Ngày tạo:</strong> {{ $user->ngay_tao->format('d/m/Y H:i') }}
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock text-muted me-2"></i>
                        <strong>Cập nhật:</strong> {{ $user->ngay_cap_nhat->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders & Activity -->
        <div class="col-lg-8">
            <!-- Order Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card bg-primary">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $stats['total_orders'] }}</div>
                            <div class="stat-label">Tổng đơn hàng</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-warning">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $stats['pending_orders'] }}</div>
                            <div class="stat-label">Chờ xử lý</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-success">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $stats['completed_orders'] }}</div>
                            <div class="stat-label">Hoàn thành</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-info">
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $stats['total_spent'] > 0 ? number_format($stats['total_spent'] / 1000000, 1) : '0' }}M</div>
                            <div class="stat-label">Tổng chi tiêu</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Đơn hàng gần đây</h5>
                </div>
                <div class="card-body">
                    @if($user->donHangs->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Ngày đặt</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Sản phẩm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->donHangs->take(10) as $donHang)
                                        <tr>
                                            <td>#{{ $donHang->ma_don_hang }}</td>
                                            <td>{{ $donHang->ngay_dat_hang->format('d/m/Y') }}</td>
                                            <td>{{ number_format($donHang->tong_tien) }}đ</td>
                                            <td>
                                                <span class="badge bg-{{ $donHang->trang_thai == 'Hoàn thành' ? 'success' : ($donHang->trang_thai == 'Chờ xử lý' ? 'warning' : 'secondary') }}">
                                                    {{ $donHang->trang_thai }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>
                                                    @foreach($donHang->chiTietDonHangs->take(2) as $chiTiet)
                                                        {{ $chiTiet->sanPham->ten_san_pham ?? 'N/A' }}
                                                        @if(!$loop->last), @endif
                                                    @endforeach
                                                    @if($donHang->chiTietDonHangs->count() > 2)
                                                        ...
                                                    @endif
                                                </small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <div class="text-muted">Chưa có đơn hàng nào</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Addresses -->
            @if($user->diaChis->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Địa chỉ</h5>
                    </div>
                    <div class="card-body">
                        @foreach($user->diaChis as $diaChi)
                            <div class="address-item {{ $loop->last ? '' : 'border-bottom' }} pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $diaChi->ten_dia_chi ?? 'Địa chỉ ' . $loop->iteration }}</strong>
                                        @if($diaChi->is_default ?? false)
                                            <span class="badge bg-primary ms-2">Mặc định</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-muted">
                                    {{ $diaChi->dia_chi_chi_tiet ?? 'N/A' }},
                                    {{ $diaChi->phuong_xa ?? 'N/A' }},
                                    {{ $diaChi->quan_huyen ?? 'N/A' }},
                                    {{ $diaChi->tinh_thanh_pho ?? 'N/A' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.avatar-lg {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #fff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.info-item {
    padding: 8px 0;
    border-bottom: 1px solid #f0f0f0;
}

.info-item:last-child {
    border-bottom: none;
}

.address-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}
</style>
@endsection