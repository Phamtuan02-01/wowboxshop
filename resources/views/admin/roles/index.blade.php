@extends('admin.layouts.app')

@section('title', 'Quản lý vai trò')

@section('content')
<div class="admin-content">
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Quản lý vai trò</h1>
                <p class="text-muted mb-0">Danh sách vai trò hệ thống</p>
            </div>
            <div>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tạo vai trò
                </a>
            </div>
        </div>
    </div>

    <div class="filter-row">
        <form method="GET" action="{{ route('admin.roles.index') }}" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tên vai trò" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button class="btn btn-outline-primary">Tìm</button>
            </div>
        </form>
    </div>

    <div class="table-container">
        <div class="table-header">
            <div>
                <strong>Danh sách vai trò</strong>
            </div>
            <div>
                <small class="text-muted">Tổng: {{ $roles->total() }}</small>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên vai trò</th>
                        <th>Người dùng</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->ma_vai_tro }}</td>
                            <td>{{ $role->ten_vai_tro }}</td>
                            <td>{{ $role->taiKhoans()->count() }}</td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.roles.edit', $role->ma_vai_tro) }}" class="btn btn-sm btn-outline-info" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.roles.destroy', $role->ma_vai_tro) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa vai trò này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination-wrapper">
            <div class="pagination-info">Hiển thị {{ $roles->firstItem() }} - {{ $roles->lastItem() }} trong {{ $roles->total() }} vai trò</div>
            <div>
                {{ $roles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
