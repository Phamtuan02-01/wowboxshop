@extends('layouts.admin')

@section('title', 'Dashboard - WowBox Admin')

@section('breadcrumb')
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@push('styles')
<style>
.chart-container {
    position: relative;
    height: 400px;
    width: 100%;
}
.chart-container.chart-small {
    height: 300px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-tachometer-alt text-primary"></i> Dashboard</h2>
            <p class="text-muted mb-0">Chào mừng bạn đến với trang quản trị WowBox Shop</p>
        </div>
        <div class="text-end">
            <small class="text-muted">
                <i class="fas fa-clock"></i> {{ now()->format('d/m/Y H:i:s') }}
            </small>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="stats-number text-primary">{{ number_format($totalUsers) }}</h3>
                            <p class="stats-label">Tổng người dùng</p>
                            <small class="stats-change positive">
                                <i class="fas fa-arrow-up"></i> +{{ $monthlyNewUsers[11]['users'] ?? 0 }} tháng này
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="stats-number text-success">{{ number_format($totalProducts) }}</h3>
                            <p class="stats-label">Tổng sản phẩm</p>
                            <small class="stats-change positive">
                                <i class="fas fa-chart-line"></i> Đang hoạt động
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="stats-number text-warning">{{ number_format($totalOrders) }}</h3>
                            <p class="stats-label">Tổng đơn hàng</p>
                            <small class="stats-change positive">
                                <i class="fas fa-arrow-up"></i> +{{ $monthlyOrders[11]['orders'] ?? 0 }} tháng này
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card stats-card border-0">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="ms-3 flex-grow-1">
                            <h3 class="stats-number text-info">{{ number_format($totalRevenue) }}₫</h3>
                            <p class="stats-label">Tổng doanh thu</p>
                            <small class="stats-change {{ $revenueChange >= 0 ? 'positive' : 'negative' }}">
                                <i class="fas fa-arrow-{{ $revenueChange >= 0 ? 'up' : 'down' }}"></i> 
                                {{ number_format(abs($revenueChange), 1) }}% so với tháng trước
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Chart -->
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-line text-primary"></i> Biểu đồ doanh thu 12 tháng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Status Chart -->
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-warning"></i> Trạng thái đơn hàng
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container chart-small">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Charts Row -->
    <div class="row mb-4">
        <!-- Daily Stats -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-success"></i> Thống kê 7 ngày qua
                    </h5>
                </div>
                <div class="card-body">
                    <div class="chart-container chart-small">
                        <canvas id="dailyStatsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-medal text-warning"></i> Top sản phẩm bán chạy
                    </h5>
                </div>
                <div class="card-body">
                    @if($topProducts->count() > 0)
                        @foreach($topProducts as $index => $product)
                        <div class="d-flex align-items-center mb-3 {{ $loop->last ? '' : 'border-bottom pb-3' }}">
                            <div class="badge bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'success') }} me-3">
                                #{{ $index + 1 }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $product->sanPham->ten_san_pham ?? 'Sản phẩm không tồn tại' }}</h6>
                                <small class="text-muted">Đã bán: {{ $product->total_sold }} sản phẩm</small>
                                <div class="progress progress-modern mt-2">
                                    <div class="progress-bar bg-{{ $index == 0 ? 'warning' : ($index == 1 ? 'secondary' : 'success') }}" 
                                         style="width: {{ ($product->total_sold / $topProducts->first()->total_sold) * 100 }}%"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center text-muted">
                            <i class="fas fa-box-open fa-3x mb-3"></i>
                            <p>Chưa có dữ liệu bán hàng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="row">
        <div class="col-12">
            <div class="card admin-table border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-shopping-cart text-primary"></i> Đơn hàng gần đây
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Mã đơn hàng</th>
                                        <th>Khách hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Sản phẩm</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td>
                                            <strong class="text-primary">#{{ $order->ma_don_hang }}</strong>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-2" style="width: 35px; height: 35px; background: #e9ecef; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                                <div>
                                                    <strong>{{ $order->taiKhoan->ten_dang_nhap ?? 'N/A' }}</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ \Carbon\Carbon::parse($order->ngay_dat)->format('d/m/Y') }}</div>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($order->ngay_dat)->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $order->chiTietDonHangs->count() }} sản phẩm</small>
                                        </td>
                                        <td>
                                            <strong class="text-success">
                                                {{ number_format($order->chiTietDonHangs->sum(function($item) {
                                                    return $item->so_luong * $item->gia;
                                                })) }}₫
                                            </strong>
                                        </td>
                                        <td>
                                            @php
                                                $statusTexts = [
                                                    'cho_xac_nhan' => 'Chờ xác nhận',
                                                    'cho_xu_ly' => 'Chờ xử lý',
                                                    'da_giao' => 'Đã giao',
                                                    'da_huy' => 'Đã hủy'
                                                ];
                                                $statusClasses = [
                                                    'cho_xac_nhan' => 'warning',
                                                    'cho_xu_ly' => 'info',
                                                    'da_giao' => 'success',
                                                    'da_huy' => 'danger'
                                                ];
                                                $statusText = $statusTexts[$order->trang_thai] ?? $order->trang_thai;
                                                $statusClass = $statusClasses[$order->trang_thai] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-outline-primary" title="Xem chi tiết">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-outline-success" title="Cập nhật">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                            <p>Chưa có đơn hàng nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dữ liệu từ PHP
    const monthlyRevenue = @json($monthlyRevenue);
    const monthlyOrders = @json($monthlyOrders);
    const orderStatus = @json($orderStatus);
    const dailyStats = @json($dailyStats);

    // Kiểm tra Chart.js
    if (typeof Chart === 'undefined') {
        console.error('Chart.js chưa được load!');
        return;
    }

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart');
    if (!revenueCtx) return;
    
    new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: monthlyRevenue.map(item => item.month),
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: monthlyRevenue.map(item => item.revenue),
            borderColor: '#2E7D32',
            backgroundColor: 'rgba(46, 125, 50, 0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.4
        }, {
            label: 'Số đơn hàng',
            data: monthlyOrders.map(item => item.orders),
            borderColor: '#FFE135',
            backgroundColor: 'rgba(255, 225, 53, 0.1)',
            borderWidth: 3,
            fill: false,
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.datasetIndex === 0) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + '₫';
                        } else {
                            return 'Đơn hàng: ' + context.parsed.y;
                        }
                    }
                }
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                ticks: {
                    callback: function(value) {
                        return new Intl.NumberFormat('vi-VN', {
                            notation: 'compact'
                        }).format(value) + '₫';
                    }
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                grid: {
                    drawOnChartArea: false,
                }
            }
        }
    }
});

    // Order Status Chart
    const statusCtx = document.getElementById('orderStatusChart');
    if (!statusCtx) return;
    
    // Hàm để lấy màu theo tên trạng thái tiếng Việt
    const getStatusColor = (status) => {
        const colorMap = {
            'Chờ xác nhận': '#ffc107',  // Vàng
            'Chờ xử lý': '#ffc107',      // Vàng
            'Đã giao': '#28a745',        // Xanh lá
            'Đã hủy': '#dc3545'          // Đỏ
        };
        return colorMap[status] || '#6c757d'; // Mặc định xám
    };
    
    new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: orderStatus.map(item => item.trang_thai || 'Chưa xác định'),
        datasets: [{
            data: orderStatus.map(item => item.count),
            backgroundColor: orderStatus.map(item => getStatusColor(item.trang_thai)),
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});

    // Daily Stats Chart
    const dailyCtx = document.getElementById('dailyStatsChart');
    if (!dailyCtx) return;
    
    new Chart(dailyCtx, {
    type: 'bar',
    data: {
        labels: dailyStats.map(item => item.date),
        datasets: [{
            label: 'Đơn hàng',
            data: dailyStats.map(item => item.orders),
            backgroundColor: 'rgba(46, 125, 50, 0.8)',
            borderColor: '#2E7D32',
            borderWidth: 1
        }, {
            label: 'Người dùng mới',
            data: dailyStats.map(item => item.users),
            backgroundColor: 'rgba(255, 225, 53, 0.8)',
            borderColor: '#FFE135',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

    // Add fade in animation
    const cards = document.querySelectorAll('.stats-card, .card');
    cards.forEach((card, index) => {
        setTimeout(() => {
            card.classList.add('fade-in-up');
        }, index * 100);
    });
});
</script>
@endsection