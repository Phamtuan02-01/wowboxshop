@extends('layouts.admin')

@section('title', 'Chi tiết khuyến mãi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.promotions.index') }}">Khuyến mãi</a></li>
    <li class="breadcrumb-item active">Chi tiết</li>
@endsection

@section('content')
<div class="admin-content">
    <!-- Header -->
    <div class="content-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title">{{ $promotion->ten_khuyen_mai }}</h1>
                <div class="d-flex align-items-center gap-2 mt-2">
                    @php
                        $statusClass = 'secondary';
                        $statusText = $promotion->trang_thai_text;
                        if ($promotion->trang_thai_text === 'Đang hoạt động') $statusClass = 'success';
                        elseif ($promotion->trang_thai_text === 'Đã kết thúc') $statusClass = 'danger';
                        elseif ($promotion->trang_thai_text === 'Chưa bắt đầu') $statusClass = 'warning';
                        elseif ($promotion->trang_thai_text === 'Đã tắt') $statusClass = 'secondary';
                    @endphp
                    <span class="badge bg-{{ $statusClass }}">{{ $statusText }}</span>
                    @if($promotion->ma_code)
                        <span class="badge bg-info">{{ $promotion->ma_code }}</span>
                    @endif
                    <span class="badge bg-primary">{{ $promotion->loai_khuyen_mai_text }}</span>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Chỉnh sửa
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Tổng sử dụng</p>
                            <h3 class="stat-value text-primary">{{ $usageStats['total_used'] }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Tổng tiết kiệm</p>
                            <h3 class="stat-value text-success">{{ number_format($usageStats['total_discount'], 0, ',', '.') }} VNĐ</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Khách hàng</p>
                            <h3 class="stat-value text-info">{{ $usageStats['unique_users'] }}</h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-users fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="stat-label">Còn lại</p>
                            <h3 class="stat-value text-warning">
                                {{ $promotion->gioi_han_su_dung ? max(0, $promotion->gioi_han_su_dung - $promotion->so_luong_su_dung) : '∞' }}
                            </h3>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-hourglass-half fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Basic Info -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin cơ bản
                    </h6>
                </div>
                <div class="p-4">
                    @if($promotion->hinh_anh)
                    <div class="mb-4">
                        <img src="{{ asset('images/promotions/' . $promotion->hinh_anh) }}" 
                             alt="{{ $promotion->ten_khuyen_mai }}" 
                             class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="140"><strong>ID:</strong></td>
                                    <td>{{ $promotion->ma_khuyen_mai }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tên khuyến mãi:</strong></td>
                                    <td>{{ $promotion->ten_khuyen_mai }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Mã code:</strong></td>
                                    <td>
                                        @if($promotion->ma_code)
                                            <span class="badge bg-secondary">{{ $promotion->ma_code }}</span>
                                            <button type="button" class="btn btn-sm btn-outline-primary ms-1" 
                                                    onclick="copyCode('{{ $promotion->ma_code }}')">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        @else
                                            <span class="text-muted">Không có</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Loại khuyến mãi:</strong></td>
                                    <td><span class="badge bg-info">{{ $promotion->loai_khuyen_mai_text }}</span></td>
                                </tr>
                                <tr>
                                    <td><strong>Giá trị giảm:</strong></td>
                                    <td><strong class="text-success">{{ $promotion->gia_tri_display }}</strong></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="140"><strong>Giá trị tối đa:</strong></td>
                                    <td>
                                        @if($promotion->gia_tri_toi_da)
                                            {{ number_format($promotion->gia_tri_toi_da, 0, ',', '.') }} VNĐ
                                        @else
                                            <span class="text-muted">Không giới hạn</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Đơn tối thiểu:</strong></td>
                                    <td>{{ number_format($promotion->don_hang_toi_thieu, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                <tr>
                                    <td><strong>Giới hạn tổng:</strong></td>
                                    <td>
                                        @if($promotion->gioi_han_su_dung)
                                            {{ $promotion->gioi_han_su_dung }} lần
                                        @else
                                            <span class="text-muted">Không giới hạn</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Giới hạn/khách:</strong></td>
                                    <td>{{ $promotion->gioi_han_moi_khach }} lần</td>
                                </tr>
                                <tr>
                                    <td><strong>Đã sử dụng:</strong></td>
                                    <td>{{ $promotion->so_luong_su_dung }} lần</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    @if($promotion->mo_ta)
                    <hr>
                    <div>
                        <h6>Mô tả:</h6>
                        <p class="text-muted">{{ $promotion->mo_ta }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Time Information -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-calendar me-2"></i>
                        Thời gian hiệu lực
                    </h6>
                </div>
                <div class="p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-calendar-plus fa-2x text-success mb-2"></i>
                                <h6>Ngày bắt đầu</h6>
                                <strong>{{ $promotion->ngay_bat_dau->format('d/m/Y H:i') }}</strong>
                                <br>
                                <small class="text-muted">{{ $promotion->ngay_bat_dau->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-calendar-minus fa-2x text-danger mb-2"></i>
                                <h6>Ngày kết thúc</h6>
                                <strong>{{ $promotion->ngay_ket_thuc->format('d/m/Y H:i') }}</strong>
                                <br>
                                <small class="text-muted">{{ $promotion->ngay_ket_thuc->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-3 p-3 bg-light rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Thời gian còn lại:</span>
                            @if($promotion->ngay_ket_thuc->isFuture())
                                <strong class="text-success">{{ $promotion->ngay_ket_thuc->diffForHumans() }}</strong>
                            @else
                                <strong class="text-danger">Đã kết thúc</strong>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Scope -->
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-bullseye me-2"></i>
                        Phạm vi áp dụng
                    </h6>
                </div>
                <div class="p-4">
                    @if($promotion->ap_dung_tat_ca)
                        <div class="alert alert-info">
                            <i class="fas fa-globe me-2"></i>
                            <strong>Áp dụng cho tất cả sản phẩm</strong>
                        </div>
                    @else
                        @if($promotion->san_pham_ap_dung)
                            <h6>Sản phẩm cụ thể:</h6>
                            <div class="row">
                                @foreach($promotion->sanPhamApDung()->get() as $product)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        @if($product->hinh_anh)
                                            <img src="{{ asset('images/products/' . $product->hinh_anh) }}" 
                                                 alt="{{ $product->ten_san_pham }}" 
                                                 class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                        @endif
                                        <span>{{ $product->ten_san_pham }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @endif
                        
                        @if($promotion->danh_muc_ap_dung)
                            <h6 class="mt-3">Danh mục sản phẩm:</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(\App\Models\DanhMuc::whereIn('ma_danh_muc', $promotion->danh_muc_ap_dung)->get() as $category)
                                    <span class="badge bg-primary">{{ $category->ten_danh_muc }}</span>
                                @endforeach
                            </div>
                        @endif
                        
                        @if(!$promotion->san_pham_ap_dung && !$promotion->danh_muc_ap_dung)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Chưa chọn sản phẩm hoặc danh mục áp dụng
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Thao tác
                    </h6>
                </div>
                <div class="p-4">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.promotions.edit', $promotion) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> Chỉnh sửa khuyến mãi
                        </a>
                        
                        <button type="button" class="btn btn-outline-{{ $promotion->trang_thai ? 'warning' : 'success' }}" 
                                onclick="toggleStatus({{ $promotion->ma_khuyen_mai }})">
                            <i class="fas fa-toggle-{{ $promotion->trang_thai ? 'off' : 'on' }} me-1"></i> 
                            {{ $promotion->trang_thai ? 'Tắt khuyến mãi' : 'Kích hoạt khuyến mãi' }}
                        </button>
                        
                        @if($promotion->ma_code)
                        <button type="button" class="btn btn-outline-info" onclick="copyCode('{{ $promotion->ma_code }}')">
                            <i class="fas fa-copy me-1"></i> Sao chép mã code
                        </button>
                        @endif
                        
                        <button type="button" class="btn btn-outline-danger" 
                                onclick="deletePromotion({{ $promotion->ma_khuyen_mai }})">
                            <i class="fas fa-trash me-1"></i> Xóa khuyến mãi
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Quick Stats -->
            <div class="table-container mb-4">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>
                        Thống kê nhanh
                    </h6>
                </div>
                <div class="p-4">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary mb-0">{{ number_format(($promotion->so_luong_su_dung / max($promotion->gioi_han_su_dung, 1)) * 100, 1) }}%</h4>
                                <small class="text-muted">Tỷ lệ sử dụng</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success mb-0">{{ number_format($usageStats['total_discount'] / max($usageStats['total_used'], 1), 0, ',', '.') }}</h4>
                            <small class="text-muted">Tiết kiệm TB</small>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="small">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Ngày tạo:</span>
                            <span>{{ $promotion->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Cập nhật cuối:</span>
                            <span>{{ $promotion->updated_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Usage History Preview -->
            @if($promotion->lichSuKhuyenMai->count() > 0)
            <div class="table-container">
                <div class="table-header">
                    <h6 class="section-title mb-0">
                        <i class="fas fa-history me-2"></i>
                        Lịch sử sử dụng gần đây
                    </h6>
                </div>
                <div class="p-4">
                    @foreach($promotion->lichSuKhuyenMai->take(5) as $usage)
                    <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                        <div>
                            <strong>{{ $usage->taiKhoan->ten_dang_nhap ?? 'N/A' }}</strong>
                            <br>
                            <small class="text-muted">{{ $usage->ngay_su_dung->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <strong class="text-success">-{{ number_format($usage->gia_tri_giam, 0, ',', '.') }} VNĐ</strong>
                            <br>
                            <small class="text-muted">Đơn #{{ $usage->ma_don_hang }}</small>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($promotion->lichSuKhuyenMai->count() > 5)
                    <div class="text-center mt-3">
                        <small class="text-muted">và {{ $promotion->lichSuKhuyenMai->count() - 5 }} lần sử dụng khác...</small>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleStatus(promotionId) {
    $.ajax({
        url: `/admin/promotions/${promotionId}/toggle-status`,
        method: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            }
        },
        error: function() {
            alert('Có lỗi xảy ra. Vui lòng thử lại!');
        }
    });
}

function deletePromotion(promotionId) {
    if (confirm('Bạn có chắc chắn muốn xóa khuyến mãi này? Hành động này không thể hoàn tác!')) {
        $.ajax({
            url: `/admin/promotions/${promotionId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                alert('Khuyến mãi đã được xóa thành công!');
                window.location.href = '{{ route("admin.promotions.index") }}';
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
            }
        });
    }
}

function copyCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        alert('Đã sao chép mã: ' + code);
    }).catch(function() {
        alert('Không thể sao chép mã. Vui lòng sao chép thủ công: ' + code);
    });
}
</script>
@endpush