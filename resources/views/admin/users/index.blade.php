@extends('admin.layouts.app')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Quản lý người dùng</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Người dùng</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.users.export') }}" class="btn btn-outline-success me-2">
                    <i class="fas fa-download"></i> Export
                </a>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm người dùng
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid mb-4">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['total'] }}</h3>
                <p>Tổng người dùng</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['admin'] }}</h3>
                <p>Quản trị viên</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <i class="fas fa-user-friends"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['customer'] }}</h3>
                <p>Khách hàng</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <i class="fas fa-user-plus"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $stats['new_this_month'] }}</h3>
                <p>Mới tháng này</p>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="{{ route('admin.users.index') }}" class="filter-form">
            <div class="row">
                <div class="col-search">
                    <div class="form-group">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" 
                               placeholder="Tên, email, số điện thoại...">
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Vai trò</label>
                        <select class="form-select" name="role">
                            <option value="">Tất cả vai trò</option>
                            @foreach($vaiTros as $vaiTro)
                                <option value="{{ $vaiTro->ma_vai_tro }}" 
                                        {{ request('role') == $vaiTro->ma_vai_tro ? 'selected' : '' }}>
                                    {{ $vaiTro->ten_vai_tro }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-select">
                    <div class="form-group">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Vô hiệu hóa</option>
                        </select>
                    </div>
                </div>
                <div class="col-button">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary" title="Tìm kiếm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-button">
                    <div class="form-group">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-light" title="Đặt lại bộ lọc">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Danh sách người dùng</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;" onchange="changeSort(this)">
                        <option value="ngay_tao-desc" {{ request('sort_by') == 'ngay_tao' && request('sort_order') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="ngay_tao-asc" {{ request('sort_by') == 'ngay_tao' && request('sort_order') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                        <option value="ho_ten-asc" {{ request('sort_by') == 'ho_ten' && request('sort_order') == 'asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="ho_ten-desc" {{ request('sort_by') == 'ho_ten' && request('sort_order') == 'desc' ? 'selected' : '' }}>Tên Z-A</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Avatar</th>
                            <th>Thông tin</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th>Trạng thái</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $user->trashed() ? 'table-secondary' : '' }}">
                                <td>{{ $user->ma_tai_khoan }}</td>
                                <td>
                                    <div class="user-avatar">
                                        <img src="{{ $user->avatar ?? asset('images/default-avatar.png') }}" 
                                             alt="Avatar" class="avatar-sm">
                                    </div>
                                </td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-name">{{ $user->ho_ten }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                        <div class="user-phone">{{ $user->so_dien_thoai }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $user->vaiTro->ten_vai_tro == 'Admin' ? 'danger' : 'primary' }}">
                                        {{ $user->vaiTro->ten_vai_tro }}
                                    </span>
                                </td>
                                <td>{{ $user->ngay_tao->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" 
                                               data-id="{{ $user->ma_tai_khoan }}"
                                               {{ !$user->trashed() ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $user->trashed() ? 'Vô hiệu hóa' : 'Hoạt động' }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.show', $user->ma_tai_khoan) }}" 
                                           class="btn btn-outline-info btn-action" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user->ma_tai_khoan) }}" 
                                           class="btn btn-outline-warning btn-action" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(!$user->trashed())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user->ma_tai_khoan) }}" 
                                                  style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-action" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('admin.users.restore', $user->ma_tai_khoan) }}" 
                                               class="btn btn-outline-success btn-action" title="Khôi phục"
                                               onclick="return confirm('Bạn có chắc muốn khôi phục người dùng này?')">
                                                <i class="fas fa-undo"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <div class="text-muted">Không có người dùng nào</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function changeSort(select) {
    const [sortBy, sortOrder] = select.value.split('-');
    const url = new URL(window.location.href);
    url.searchParams.set('sort_by', sortBy);
    url.searchParams.set('sort_order', sortOrder);
    window.location.href = url.toString();
}

// Toggle user status
$(document).ready(function() {
    $('.status-toggle').change(function() {
        const userId = $(this).data('id');
        const toggle = $(this);
        
        $.ajax({
            url: `/admin/users/${userId}/toggle-status`,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Update label
                    const label = toggle.next('label');
                    label.text(toggle.is(':checked') ? 'Hoạt động' : 'Vô hiệu hóa');
                    
                    // Update row style
                    const row = toggle.closest('tr');
                    if (toggle.is(':checked')) {
                        row.removeClass('table-secondary');
                    } else {
                        row.addClass('table-secondary');
                    }
                    
                    // Show success message
                    showAlert('success', response.message);
                } else {
                    toggle.prop('checked', !toggle.is(':checked'));
                    showAlert('error', 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            },
            error: function() {
                toggle.prop('checked', !toggle.is(':checked'));
                showAlert('error', 'Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });
});

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alert = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('.admin-content').prepend(alert);
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        $('.alert').fadeOut();
    }, 3000);
}
</script>
@endsection