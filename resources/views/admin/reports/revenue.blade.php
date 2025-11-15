@extends('admin.layouts.app')

@section('title', 'Báo cáo Doanh thu')

@section('content')
<div class="admin-content">
    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fas fa-chart-line"></i> Báo cáo Doanh thu</h1>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">Bộ lọc</h5>
                <a href="{{ route('admin.reports.revenue.export') }}?start_date={{ $startDate }}&end_date={{ $endDate }}&period={{ $period }}" 
                   class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Xuất Excel
                </a>
            </div>
            <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Chu kỳ</label>
                    <select name="period" class="form-select">
                        <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Theo ngày</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>Theo tuần</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Theo tháng</option>
                    </select>
                </div>
                <div class="col-md-3">
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
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['total_revenue'], 0, ',', '.') }}</h3>
                    <p>Tổng doanh thu (VNĐ)</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
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
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="stat-info">
                    <h3>{{ number_format($overview['average_order_value'], 0, ',', '.') }}</h3>
                    <p>Giá trị đơn TB (VNĐ)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Comparison -->
    <div class="card mb-4">
        <div class="card-header">
            <h5><i class="fas fa-exchange-alt"></i> So sánh kỳ trước</h5>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <div class="text-center">
                        <h6 class="text-muted">Kỳ trước</h6>
                        <h3>{{ number_format($comparison['previous_revenue'], 0, ',', '.') }} VNĐ</h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <i class="fas fa-arrow-{{ $comparison['is_increase'] ? 'up text-success' : 'down text-danger' }} fa-3x"></i>
                        <h3 class="mt-2 {{ $comparison['is_increase'] ? 'text-success' : 'text-danger' }}">
                            {{ $comparison['is_increase'] ? '+' : '' }}{{ number_format(abs($comparison['change_percent']), 1) }}%
                        </h3>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h6 class="text-muted">Kỳ hiện tại</h6>
                        <h3>{{ number_format($comparison['current_revenue'], 0, ',', '.') }} VNĐ</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-chart-area"></i> Biểu đồ doanh thu</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>

            <!-- Top Products -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-trophy"></i> Top sản phẩm bán chạy</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Số lượng</th>
                                    <th class="text-end">Doanh thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->sanPham && $item->sanPham->hinh_anh)
                                            <img src="{{ asset('images/products/' . $item->sanPham->hinh_anh) }}" 
                                                 alt="{{ $item->sanPham->ten_san_pham }}"
                                                 class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            @endif
                                            <span>{{ $item->sanPham->ten_san_pham ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($item->total_quantity) }}</td>
                                    <td class="text-end fw-bold text-success">{{ number_format($item->total_revenue, 0, ',', '.') }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Category -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5><i class="fas fa-chart-pie"></i> Doanh thu theo danh mục</h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-list"></i> Chi tiết danh mục</h5>
                </div>
                <div class="card-body">
                    @foreach($revenueByCategory as $category)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>{{ $category->ten_danh_muc }}</strong>
                            <span class="badge bg-primary">{{ number_format($category->total_quantity) }} SP</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $revenueByCategory->sum('total_revenue') > 0 ? ($category->total_revenue / $revenueByCategory->sum('total_revenue')) * 100 : 0 }}%">
                            </div>
                        </div>
                        <small class="text-muted">{{ number_format($category->total_revenue, 0, ',', '.') }} VNĐ</small>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueData = @json($revenueByTime);
const labels = Object.keys(revenueData);
const revenues = labels.map(label => revenueData[label].revenue);
const orders = labels.map(label => revenueData[label].orders);

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Doanh thu (VNĐ)',
            data: revenues,
            borderColor: 'rgb(102, 126, 234)',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y'
        }, {
            label: 'Đơn hàng',
            data: orders,
            borderColor: 'rgb(67, 233, 123)',
            backgroundColor: 'rgba(67, 233, 123, 0.1)',
            tension: 0.4,
            fill: true,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
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
                        if (context.datasetIndex === 0) {
                            label += new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                        } else {
                            label += context.parsed.y;
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
                        return new Intl.NumberFormat('vi-VN', { notation: 'compact' }).format(value);
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

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
const categoryData = @json($revenueByCategory);
const categoryLabels = categoryData.map(item => item.ten_danh_muc);
const categoryRevenues = categoryData.map(item => item.total_revenue);

new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: categoryLabels,
        datasets: [{
            data: categoryRevenues,
            backgroundColor: [
                'rgba(102, 126, 234, 0.8)',
                'rgba(67, 233, 123, 0.8)',
                'rgba(79, 172, 254, 0.8)',
                'rgba(250, 112, 154, 0.8)',
                'rgba(254, 225, 64, 0.8)',
                'rgba(168, 237, 234, 0.8)',
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.label || '';
                        if (label) {
                            label += ': ';
                        }
                        label += new Intl.NumberFormat('vi-VN').format(context.parsed) + ' VNĐ';
                        return label;
                    }
                }
            }
        }
    }
});
</script>
@endpush
