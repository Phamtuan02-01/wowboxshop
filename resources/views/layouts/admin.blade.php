<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - WowBox Shop')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('css/admin-orders.css') }}" rel="stylesheet">
    
    <!-- Custom Admin Theme -->
    <style>
        /* Màu vàng nhạt cho theme shop */
        .top-navbar {
            background: linear-gradient(135deg, #ffe135 0%, #fffbf0 80%) !important;
            border-bottom: 1px solid #f5f5dc;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        .page-content {
            background: linear-gradient(to bottom, #fffef7 0%, #fdfcf0 100%) !important;
            min-height: 100vh;
        }
        
        /* Điều chỉnh màu text để phù hợp với nền vàng nhạt */
        .top-navbar .breadcrumb-item a {
            color: #8b7355 !important;
        }
        
        .top-navbar .breadcrumb-item.active {
            color: #6b5b3f !important;
        }
        
        /* Cập nhật màu button trên header */
        .top-navbar .btn-outline-primary {
            border-color: #d4b106;
            color: #d4b106;
        }
        
        .top-navbar .btn-outline-primary:hover {
            background-color: #d4b106;
            border-color: #d4b106;
            color: white;
        }
        
        .top-navbar .btn-outline-secondary {
            border-color: #8b7355;
            color: #8b7355;
        }
        
        .top-navbar .btn-outline-secondary:hover {
            background-color: #8b7355;
            border-color: #8b7355;
            color: white;
        }
        
        /* Sidebar toggle button */
        .sidebar-toggle-btn {
            color: #6b5b3f !important;
        }
        
        .sidebar-toggle-btn:hover {
            background-color: rgba(107, 91, 63, 0.1) !important;
        }
        
        /* Table containers trên nền vàng nhạt */
        .table-container {
            background: white;
            box-shadow: 0 2px 8px rgba(107, 91, 63, 0.1);
        }
        
        /* Card shadows */
        .card {
            box-shadow: 0 2px 8px rgba(107, 91, 63, 0.08);
        }
        
        /* Admin content area */
        .admin-content {
            background: transparent;
        }
        
        /* Content header */
        .content-header {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            margin-bottom: 1.5rem;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(107, 91, 63, 0.08);
        }
        
        .content-title {
            color: #6b5b3f;
        }
        
        /* Stat cards */
        .stat-card {
            background: white;
            box-shadow: 0 2px 8px rgba(107, 91, 63, 0.08);
            border: 1px solid rgba(212, 177, 6, 0.1);
        }
        
        /* Buttons với theme vàng */
        .btn-primary {
            background: linear-gradient(135deg, #d4b106 0%, #b8960a 100%);
            border-color: #d4b106;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #b8960a 0%, #9c7f08 100%);
            border-color: #b8960a;
        }
        
        /* Focus states */
        .form-control:focus {
            border-color: #d4b106;
            box-shadow: 0 0 0 0.25rem rgba(212, 177, 6, 0.15);
        }
        
        .form-select:focus {
            border-color: #d4b106;
            box-shadow: 0 0 0 0.25rem rgba(212, 177, 6, 0.15);
        }
    </style>
    
    @yield('styles')
    @stack('styles')
</head>
<body class="admin-layout">
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-brand">
                <img src="{{ asset('images/Logo.png') }}" alt="WowBox" class="sidebar-logo">
                <span class="brand-text">WowBox Admin</span>
            </div>
            <button class="sidebar-toggle d-lg-none" id="sidebarToggle">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-menu">
            <!-- User Info -->
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">{{ Auth::user()->ten_dang_nhap }}</div>
                    <div class="user-role">Administrator</div>
                </div>
            </div>
            
            <!-- Menu Items -->
            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-header">QUẢN LÝ HỆ THỐNG</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Quản lý người dùng</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.roles.index') }}" class="nav-link {{ Request::is('admin/roles*') ? 'active' : '' }}">
                        <i class="fas fa-user-tag"></i>
                        <span>Quản lý vai trò</span>
                    </a>
                </li>
                
                <li class="nav-header">QUẢN LÝ SẢN PHẨM</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Danh mục sản phẩm</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ Request::is('admin/products*') ? 'active' : '' }}">
                        <i class="fas fa-box"></i>
                        <span>Sản phẩm</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.product-variants.index') }}" class="nav-link {{ Request::is('admin/product-variants*') ? 'active' : '' }}">
                        <i class="fas fa-layer-group"></i>
                        <span>Biến thể sản phẩm</span>
                    </a>
                </li>
                
                <li class="nav-header">QUẢN LÝ BÁN HÀNG</li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Đơn hàng</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.promotions.index') }}" class="nav-link {{ Request::is('admin/promotions*') ? 'active' : '' }}">
                        <i class="fas fa-tags"></i>
                        <span>Khuyến mãi</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-star"></i>
                        <span>Đánh giá</span>
                    </a>
                </li>
                
                <li class="nav-header">BÁO CÁO & THỐNG KÊ</li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-line"></i>
                        <span>Báo cáo doanh thu</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-pie"></i>
                        <span>Thống kê bán hàng</span>
                    </a>
                </li>
                
                <li class="nav-header">CÀI ĐẶT</li>
                
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Cài đặt hệ thống</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <!-- Logout -->
        <div class="sidebar-footer">
            <form action="{{ route('dangxuat') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Đăng xuất</span>
                </button>
            </form>
        </div>
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navigation -->
        <header class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle-btn" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        @yield('breadcrumb')
                    </ol>
                </nav>
            </div>
            
            <div class="navbar-right">
                <div class="navbar-item">
                    <button class="btn btn-outline-primary btn-sm" onclick="window.open('{{ route('trangchu') }}', '_blank')">
                        <i class="fas fa-external-link-alt"></i> Xem website
                    </button>
                </div>
                
                <div class="navbar-item dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> {{ Auth::user()->ten_dang_nhap }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog"></i> Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog"></i> Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('dangxuat') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <main class="page-content">
            @yield('content')
        </main>
    </div>
    
    <!-- Overlay for mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    
    <!-- Flash Messages Component -->
    @include('components.flash-messages')
    
    <!-- Custom Alert Component -->
    @include('components.custom-alert')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Setup AJAX with CSRF Token -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    
    <!-- Admin JS -->
    <script>
        // Sidebar toggle functionality
        const sidebar = document.getElementById('sidebar');
        const sidebarToggleBtn = document.getElementById('sidebarToggleBtn');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        
        function toggleSidebar() {
            sidebar.classList.toggle('collapsed');
            document.body.classList.toggle('sidebar-open');
        }
        
        sidebarToggleBtn?.addEventListener('click', toggleSidebar);
        sidebarToggle?.addEventListener('click', toggleSidebar);
        sidebarOverlay?.addEventListener('click', toggleSidebar);
        
        // Close sidebar on mobile when clicking on menu items
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) {
                    toggleSidebar();
                }
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                document.body.classList.remove('sidebar-open');
                sidebar.classList.remove('collapsed');
            }
        });
    </script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    
    <!-- Custom Confirm Helper -->
    <script>
        // Helper function to replace default confirm with custom confirm
        function customConfirm(message) {
            return new Promise((resolve) => {
                if (typeof showCustomConfirm === 'function') {
                    showCustomConfirm(message, resolve);
                } else {
                    resolve(confirm(message));
                }
            });
        }
        
        // Replace all form onsubmit confirms with custom confirm
        document.addEventListener('DOMContentLoaded', function() {
            // Handle form submissions with confirm
            document.querySelectorAll('form[onsubmit*="confirm"]').forEach(form => {
                const originalOnsubmit = form.getAttribute('onsubmit');
                const confirmMatch = originalOnsubmit.match(/confirm\('([^']+)'\)/);
                
                if (confirmMatch) {
                    const message = confirmMatch[1];
                    form.removeAttribute('onsubmit');
                    
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        customConfirm(message).then(result => {
                            if (result) {
                                // Remove the event listener to avoid infinite loop
                                form.removeEventListener('submit', arguments.callee);
                                form.submit();
                            }
                        });
                    });
                }
            });
            
            // Handle links/buttons with onclick confirm
            document.querySelectorAll('[onclick*="confirm"]').forEach(element => {
                const originalOnclick = element.getAttribute('onclick');
                const confirmMatch = originalOnclick.match(/confirm\('([^']+)'\)/);
                
                if (confirmMatch) {
                    const message = confirmMatch[1];
                    element.removeAttribute('onclick');
                    
                    element.addEventListener('click', function(e) {
                        e.preventDefault();
                        customConfirm(message).then(result => {
                            if (result) {
                                // Execute original action after removing confirm
                                const action = originalOnclick.replace(/return\s+confirm\([^)]+\);?\s*/, '');
                                if (action && action.trim()) {
                                    eval(action);
                                }
                                // If it's a link, follow it
                                if (element.tagName === 'A' && element.href) {
                                    window.location.href = element.href;
                                }
                            }
                        });
                    });
                }
            });
        });
    </script>
    
    @yield('scripts')
    @stack('scripts')
</body>
</html>