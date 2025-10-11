@extends('layouts.admin')

@section('title', 'Quản lý danh mục sản phẩm')

@section('breadcrumb')
    <li class="breadcrumb-item active">Danh mục sản phẩm</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Quản lý danh mục sản phẩm</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Danh mục sản phẩm</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm danh mục
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-primary">
                    <i class="fas fa-list"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['total_categories'] }}</div>
                    <div class="stat-label">Tổng danh mục</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-success">
                    <i class="fas fa-sitemap"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['root_categories'] }}</div>
                    <div class="stat-label">Danh mục gốc</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-info">
                    <i class="fas fa-layer-group"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['child_categories'] }}</div>
                    <div class="stat-label">Danh mục con</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon bg-warning">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">{{ $stats['categories_with_products'] }}</div>
                    <div class="stat-label">Có sản phẩm</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="filter-row">
        <form method="GET" action="{{ route('admin.categories.index') }}">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search">Tìm kiếm theo tên</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Nhập tên danh mục...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="parent_id">Danh mục cha</label>
                        <select class="form-control" id="parent_id" name="parent_id">
                            <option value="">Tất cả</option>
                            <option value="root" {{ request('parent_id') == 'root' ? 'selected' : '' }}>Danh mục gốc</option>
                            @foreach($parentCategories as $parent)
                                <option value="{{ $parent->ma_danh_muc }}" {{ request('parent_id') == $parent->ma_danh_muc ? 'selected' : '' }}>
                                    {{ $parent->ten_danh_muc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search"></i> Tìm kiếm
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Xóa lọc
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Categories Table -->
    <div class="table-container">
        <div class="table-header">
            <h6 class="section-title mb-0">Danh sách danh mục</h6>
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="exportData()">
                        <i class="fas fa-download me-2"></i>Xuất Excel
                    </a></li>
                </ul>
            </div>
        </div>
        
        @if($categories->count() > 0)
            <div class="table-responsive">
                <table class="table custom-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên danh mục</th>
                            <th>Danh mục cha</th>
                            <th>Số danh mục con</th>
                            <th>Số sản phẩm</th>
                            <th>Ngày tạo</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->ma_danh_muc }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($category->ma_danh_muc_cha)
                                            <i class="fas fa-level-up-alt text-muted me-2" style="transform: rotate(90deg);"></i>
                                        @else
                                            <i class="fas fa-folder text-primary me-2"></i>
                                        @endif
                                        <strong>{{ $category->ten_danh_muc }}</strong>
                                    </div>
                                </td>
                                <td>
                                    @if($category->danhMucCha)
                                        <span class="badge bg-secondary">{{ $category->danhMucCha->ten_danh_muc }}</span>
                                    @else
                                        <span class="text-muted">Danh mục gốc</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $category->danh_muc_cons_count ?? $category->danhMucCons->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $category->san_phams_count ?? $category->sanPhams->count() }}</span>
                                </td>
                                <td>{{ $category->created_at ? $category->created_at->format('d/m/Y H:i') : 'Không có' }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.categories.show', $category) }}" 
                                           class="btn btn-outline-info btn-action" 
                                           title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="btn btn-outline-warning btn-action" 
                                           title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-action" 
                                                    title="Xóa"
                                                    {{ ($category->sanPhams->count() > 0 || $category->danhMucCons->count() > 0) ? 'disabled' : '' }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination-wrapper">
                <div class="pagination-summary">
                    <div class="pagination-info">
                        Hiển thị <strong>{{ $categories->firstItem() ?? 0 }}</strong> đến <strong>{{ $categories->lastItem() ?? 0 }}</strong> 
                        trong tổng số <span class="badge">{{ number_format($categories->total()) }}</span> danh mục
                        @if(request()->has('search') || request()->has('parent_id'))
                            <small class="text-muted ms-2">
                                <i class="fas fa-filter"></i> (đã lọc)
                            </small>
                        @endif
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    @if($categories->hasPages())
                        <div class="pagination-controls">
                            <small class="text-muted me-2">Trang {{ $categories->currentPage() }} / {{ $categories->lastPage() }}</small>
                        </div>
                    @endif
                    {{ $categories->appends(request()->query())->links('custom.pagination') }}
                </div>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-secondary mb-3"></i>
                <h5 class="text-secondary">Không tìm thấy danh mục nào</h5>
                <p class="text-muted">Hãy thêm danh mục đầu tiên cho cửa hàng của bạn.</p>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm danh mục đầu tiên
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function exportData() {
    // Implement export functionality
    alert('Chức năng xuất dữ liệu sẽ được triển khai sau');
}

// Auto submit form when filter changes
document.getElementById('parent_id')?.addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection