@extends('admin.layouts.app')

@section('title', 'Thống kê Bán hàng')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-shopping-bag"></i> Thống kê Bán hàng</h1>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Bộ lọc</h5>
                <a href="{{ route('admin.reports.sales.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>
            <form method="GET" action="{{ route('admin.reports.sales') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter"></i> Lọc
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Overview Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['total_orders']) }}</h3>
                    <p>Tổng đơn hàng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['completed_orders']) }}</h3>
                    <p>Đơn hoàn thành</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <i class="fas fa-box"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['total_products_sold']) }}</h3>
                    <p>Sản phẩm đã bán</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['total_revenue'], 0, ',', '.') }}</h3>
                    <p>Tổng doanh thu (VNĐ)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-spinner"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['processing_orders']) }}</h3>
                    <p>Đơn đang xử lý</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #ff6a88 0%, #ff99ac 100%);">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['cancelled_orders']) }}</h3>
                    <p>Đơn đã hủy</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['conversion_rate'], 1) }}%</h3>
                    <p>Tỷ lệ chuyển đổi</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #fccb90 0%, #d57eeb 100%);">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['average_order_value'], 0, ',', '.') }}</h3>
                    <p>Giá trị đơn TB (VNĐ)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Daily Sales Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-area"></i> Doanh số theo ngày</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailySalesChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Chart -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie"></i> Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <canvas id="orderStatusChart"></canvas>
                    <div class="mt-3">
                        @foreach($orderStatusStats as $status)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary">{{ $status->status_name }}</span>
                            <span><strong>{{ number_format($status->count) }}</strong> đơn</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Stats & Low Stock -->
    <div class="row mb-4">
        <!-- Category Stats -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-th-large"></i> Thống kê theo danh mục</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Danh mục</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-end">Doanh thu</th>
                                    <th class="text-end">SP</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categoryStats as $category)
                                <tr>
                                    <td><strong>{{ $category->ten_danh_muc }}</strong></td>
                                    <td class="text-end">{{ number_format($category->total_quantity) }}</td>
                                    <td class="text-end">{{ number_format($category->total_revenue, 0, ',', '.') }} ₫</td>
                                    <td class="text-end"><span class="badge bg-info">{{ $category->product_count }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5><i class="fas fa-exclamation-triangle"></i> Sản phẩm sắp hết hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Biến thể</th>
                                    <th class="text-end">Tồn kho</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockProducts as $product)
                                    @foreach($product->bienThes as $variant)
                                    <tr>
                                        <td>
                                            <strong>{{ $product->ten_san_pham }}</strong>
                                            <br><small class="text-muted">{{ $product->ma_san_pham }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $variant->kich_thuoc }}</span>
                                        </td>
                                        <td class="text-end">
                                            <span class="badge {{ $variant->so_luong_ton <= 5 ? 'bg-danger' : 'bg-warning' }}">
                                                {{ number_format($variant->so_luong_ton) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-success">
                                        <i class="fas fa-check-circle"></i> Tất cả sản phẩm đều đủ hàng
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top & Slow Selling Products -->
    <div class="row mb-4">
        <!-- Top Selling Products -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5><i class="fas fa-fire"></i> Top sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Đã bán</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $index => $product)
                                <tr>
                                    <td>
                                        @if($index < 3)
                                            <span class="badge bg-warning">{{ $index + 1 }}</span>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $product->sanPham->ten_san_pham ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $product->sanPham->ma_san_pham ?? '' }}</small>
                                    </td>
                                    <td class="text-end"><strong>{{ number_format($product->total_sold) }}</strong></td>
                                    <td class="text-end">{{ number_format($product->total_revenue, 0, ',', '.') }} ₫</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slow Selling Products -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5><i class="fas fa-turtle"></i> Sản phẩm bán chậm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Đã bán</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slowProducts as $product)
                                <tr>
                                    <td>
                                        <strong>{{ $product->sanPham->ten_san_pham ?? 'N/A' }}</strong>
                                        <br><small class="text-muted">{{ $product->sanPham->ma_san_pham ?? '' }}</small>
                                    </td>
                                    <td class="text-end">{{ number_format($product->total_sold) }}</td>
                                    <td class="text-end">{{ number_format($product->total_revenue, 0, ',', '.') }} ₫</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Không có dữ liệu</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Daily Sales Chart
    const dailySalesData = @json($dailySales);
    const dailyLabels = Object.keys(dailySalesData);
    const dailyRevenue = dailyLabels.map(date => dailySalesData[date].revenue);
    const dailyOrders = dailyLabels.map(date => dailySalesData[date].orders);

    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: dailyLabels.map(date => {
                const d = new Date(date);
                return d.getDate() + '/' + (d.getMonth() + 1);
            }),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: dailyRevenue,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y'
            }, {
                label: 'Số đơn hàng',
                data: dailyOrders,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.1)',
                tension: 0.4,
                fill: true,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' ₫';
                                } else {
                                    label += context.parsed.y + ' đơn';
                                }
                            }
                            return label;
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
                                notation: 'compact',
                                compactDisplay: 'short'
                            }).format(value);
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
    const orderStatusData = @json($orderStatusStats);
    const statusLabels = orderStatusData.map(item => item.status_name);
    const statusCounts = orderStatusData.map(item => item.count);

    const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
    new Chart(orderStatusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection
