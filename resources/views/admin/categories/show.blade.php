@extends('layouts.admin')

@section('title', 'Chi tiết danh mục: ' . $category->ten_danh_muc)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
    <li class="breadcrumb-item active">{{ $category->ten_danh_muc }}</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">Chi tiết danh mục: {{ $category->ten_danh_muc }}</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
                        <li class="breadcrumb-item active">{{ $category->ten_danh_muc }}</li>
                    </ol>
                </nav>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Category Info Row -->
    <div class="row mb-4">
        <div class="col-lg-4">
            <!-- Category Info Card -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">Thông tin danh mục</h6>
                </div>
                <div style="padding: 1.5rem;">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>ID:</strong></td>
                            <td>{{ $category->ma_danh_muc }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tên danh mục:</strong></td>
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
                        </tr>
                        <tr>
                            <td><strong>Danh mục cha:</strong></td>
                            <td>
                                @if($category->danhMucCha)
                                    <a href="{{ route('admin.categories.show', $category->danhMucCha) }}" class="badge bg-secondary text-decoration-none">
                                        {{ $category->danhMucCha->ten_danh_muc }}
                                    </a>
                                @else
                                    <span class="badge bg-primary">Danh mục gốc</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Cấp độ:</strong></td>
                            <td>
                                @if($category->ma_danh_muc_cha)
                                    <span class="badge bg-info">Cấp 2</span>
                                @else
                                    <span class="badge bg-primary">Cấp 1</span>
                                @endif
                            </td>
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
        </div>

        <div class="col-lg-8">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $stats['total_products'] }}</div>
                            <div class="stat-label">Sản phẩm trực tiếp</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
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

                <div class="col-md-4 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon bg-warning">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <div class="stat-content">
                            <div class="stat-number">{{ $stats['total_products_including_children'] }}</div>
                            <div class="stat-label">Tổng sản phẩm</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Child Categories -->
    @if($category->danhMucCons()->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">Danh mục con ({{ $category->danhMucCons()->count() }})</h6>
                </div>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên danh mục</th>
                                <th>Số sản phẩm</th>
                                <th>Ngày tạo</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($category->danhMucCons as $child)
                                <tr>
                                    <td>{{ $child->ma_danh_muc }}</td>
                                    <td>
                                        <i class="fas fa-level-up-alt text-muted me-2" style="transform: rotate(90deg);"></i>
                                        {{ $child->ten_danh_muc }}
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $child->sanPhams()->count() }}</span>
                                    </td>
                                    <td>{{ $child->created_at ? $child->created_at->format('d/m/Y') : 'Không có' }}</td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('admin.categories.show', $child) }}" 
                                               class="btn btn-outline-info btn-action" 
                                               title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.categories.edit', $child) }}" 
                                               class="btn btn-outline-warning btn-action" 
                                               title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Products in Category -->
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        Sản phẩm trong danh mục ({{ $products->total() }})
                    </h6>
                    @if($products->count() > 0)
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="exportProducts()">
                                <i class="fas fa-download me-2"></i>Xuất danh sách sản phẩm
                            </a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                @if($products->count() > 0)
                    <div class="table-responsive">
                        <table class="table custom-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Trạng thái</th>
                                    <th>Ngày tạo</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{ $product->ma_san_pham }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($product->hinh_anh)
                                                    <img src="{{ asset('images/products/' . $product->hinh_anh) }}" 
                                                         alt="{{ $product->ten_san_pham }}" 
                                                         class="rounded me-2" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $product->ten_san_pham }}</strong>
                                                    @if($product->mo_ta)
                                                        <br><small class="text-muted">{{ Str::limit($product->mo_ta, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong class="text-success">{{ number_format($product->gia, 0, ',', '.') }}đ</strong>
                                        </td>
                                        <td>
                                            @if($product->trang_thai)
                                                <span class="badge bg-success">Đang bán</span>
                                            @else
                                                <span class="badge bg-secondary">Tạm ngưng</span>
                                            @endif
                                        </td>
                                        <td>{{ $product->created_at ? $product->created_at->format('d/m/Y') : 'Không có' }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.products.show', $product) }}" 
                                                   class="btn btn-outline-info btn-action" 
                                                   title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.products.edit', $product) }}" 
                                                   class="btn btn-outline-warning btn-action" 
                                                   title="Chỉnh sửa">
                                                    <i class="fas fa-edit"></i>
                                                </a>
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
                                Hiển thị <strong>{{ $products->firstItem() ?? 0 }}</strong> đến <strong>{{ $products->lastItem() ?? 0 }}</strong> 
                                trong tổng số <span class="badge">{{ $products->total() }}</span> sản phẩm
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            {{ $products->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-box-open fa-3x text-secondary mb-3"></i>
                        <h5 class="text-secondary">Chưa có sản phẩm nào trong danh mục này</h5>
                        <p class="text-muted">Hãy thêm sản phẩm đầu tiên cho danh mục này.</p>
                        <a href="{{ route('admin.products.create') }}?category={{ $category->ma_danh_muc }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Thêm sản phẩm
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function exportProducts() {
    // Implement export functionality
    alert('Chức năng xuất danh sách sản phẩm sẽ được triển khai sau');
}
</script>
@endsection