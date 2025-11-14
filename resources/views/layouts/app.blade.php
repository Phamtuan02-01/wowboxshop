<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'WowBox Shop')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/header-footer.css') }}" rel="stylesheet">
    
    @yield('styles')
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-container">
            <!-- Logo -->
            <div class="logo-section">
                <div class="logo">
                    <img class="image-logo" src="{{ asset('images/logo.png') }}" alt="WowBox Shop">
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="main-nav">
                <div class="nav-item">
                    <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('trangchu') }}">
                        Trang chủ
                    </a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ Request::is('ve-chung-toi') ? 'active' : '' }}" href="{{ route('vechungtoi') }}">Về chúng tôi</a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ Request::is('dat-mon*') ? 'active' : '' }}" href="{{ route('dat-mon.index') }}">Đặt món</a>
                </div>
                <div class="nav-item">
                    <a class="nav-link {{ Request::is('tu-chon*') ? 'active' : '' }}" href="{{ route('tu-chon.index') }}">Tự chọn</a>
                </div>
                <div class="nav-item">
                    <a class="nav-link" href="#">Blog</a>
                </div>
            </nav>
            
            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Giỏ hàng -->
                <div class="position-relative">
                    <a href="{{ route('giohang') }}" class="action-btn text-decoration-none" title="Giỏ hàng">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-badge" id="cart-count">
                            @auth
                                @php
                                    $gioHang = Auth::user()->gioHang;
                                    $tongSoLuong = $gioHang ? $gioHang->chiTietGioHangs->sum('so_luong') : 0;
                                @endphp
                                {{ $tongSoLuong }}
                            @else
                                0
                            @endauth
                        </span>
                    </a>
                </div>
                
                <!-- Tài khoản -->
                @auth
                    <div class="dropdown">
                        <button class="action-btn dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" title="Tài khoản">
                            <i class="fas fa-user"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <h6 class="dropdown-header">
                                    <i class="fas fa-user me-2"></i>{{ Auth::user()->ten_dang_nhap }}
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            @if(Auth::user()->vaiTro->ten_vai_tro === 'Admin')
                                <li>
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.index') }}">
                                    <i class="fas fa-user-cog me-2"></i>Thông tin cá nhân
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.history') }}">
                                    <i class="fas fa-history me-2"></i>Lịch sử đơn hàng
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('dangxuat') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('dangnhap') }}" class="action-btn text-decoration-none" title="Đăng nhập">
                        <i class="fas fa-user"></i>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @include('components.flash-messages')

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Advanced Chatbot Widget V2 -->
    @include('components.chatbot-v2')

    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <!-- Contact Info -->
            <div class="footer-contact">
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">Thứ Hai - Chủ Nhật</div>
                        <div class="contact-value">8:30 - 20:45</div>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">654 Lương Hữu Khánh</div>
                        <div class="contact-value">Phường Phạm Ngũ Lão<br>Quận 1, TP.HCM</div>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">028.6685.9055</div>
                        <div class="contact-value">028.6682.8055</div>
                    </div>
                </div>
                
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-text">
                        <div class="contact-label">biz.wowbox@gmail.com</div>
                    </div>
                </div>
            </div>
            
            <!-- Logo -->
            <div class="footer-logo">
                <h2>WOWBOX</h2>
            </div>
            
            <!-- Social Media -->
            <div class="footer-social">
                <div class="social-icons">
                    <a href="#" class="social-btn facebook" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="social-btn youtube" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="social-btn foody" title="Foody">
                        <i class="fas fa-utensils"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Toast Notifications JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tự động ẩn tất cả toast notifications sau 3 giây
            const toasts = document.querySelectorAll('.flash-toast');
            toasts.forEach(function(toast) {
                setTimeout(function() {
                    closeToast(toast.querySelector('.btn-close'));
                }, 3000); // 3 giây
            });
        });

        // Function để đóng toast
        function closeToast(button) {
            const toast = button.closest('.flash-toast');
            if (toast) {
                toast.classList.add('fade-out');
                setTimeout(function() {
                    toast.remove();
                }, 400); // Đợi animation hoàn thành
            }
        }

        // Function để tạo toast notification từ JavaScript
        function showToast(type, title, message) {
            const container = document.getElementById('toastContainer');
            if (!container) return;

            const icons = {
                'success': 'fas fa-check',
                'error': 'fas fa-times',
                'warning': 'fas fa-exclamation',
                'info': 'fas fa-info'
            };

            const titles = {
                'success': 'Thành công!',
                'error': 'Lỗi!',
                'warning': 'Cảnh báo!',
                'info': 'Thông tin!'
            };

            const toastDiv = document.createElement('div');
            toastDiv.className = `flash-toast toast-${type}`;
            toastDiv.setAttribute('role', 'alert');
            
            toastDiv.innerHTML = `
                <div class="toast-header">
                    <div class="toast-icon">
                        <i class="${icons[type]}"></i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${title || titles[type]}</div>
                    </div>
                    <button type="button" class="btn-close" onclick="closeToast(this)" aria-label="Close">
                        ×
                    </button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
                <div class="progress-bar"></div>
            `;
            
            container.appendChild(toastDiv);
            
            // Tự động ẩn sau 3 giây
            setTimeout(function() {
                closeToast(toastDiv.querySelector('.btn-close'));
            }, 3000);
        }

        // Make showToast globally available
        window.showToast = showToast;

        // Alias functions để dễ sử dụng
        function showSuccess(message, title) { showToast('success', title, message); }
        function showError(message, title) { showToast('error', title, message); }
        function showWarning(message, title) { showToast('warning', title, message); }
        function showInfo(message, title) { showToast('info', title, message); }
            
            container.appendChild(alertDiv);
            
            // Tự động ẩn sau 3 giây
            setTimeout(function() {
                if (alertDiv && !alertDiv.classList.contains('d-none')) {
                    alertDiv.classList.add('fade-out');
                    setTimeout(function() {
                        if (alertDiv.parentNode) {
                            alertDiv.parentNode.removeChild(alertDiv);
                        }
                    }, 500);
                }
            }, 3000);
        }

        // Function để đóng thông báo thủ công
        function closeFlashMessage(button) {
            const alert = button.closest('.flash-alert');
            if (alert) {
                alert.classList.add('fade-out');
                setTimeout(function() {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 500);
            }
        }

        // Thêm event listener cho tất cả nút close hiện có
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-close') || e.target.closest('.btn-close')) {
                const button = e.target.classList.contains('btn-close') ? e.target : e.target.closest('.btn-close');
                closeFlashMessage(button);
                e.preventDefault();
            }
        });

        // Cập nhật số lượng giỏ hàng
        function updateCartCount() {
            @auth
                fetch('{{ route("giohang.count") }}')
                    .then(response => response.json())
                    .then(data => {
                        const cartBadge = document.getElementById('cart-count');
                        if (cartBadge) {
                            const oldCount = parseInt(cartBadge.textContent) || 0;
                            const newCount = data.count || 0;
                            
                            cartBadge.textContent = newCount;
                            
                            // Hiển thị hoặc ẩn badge
                            if (newCount > 0) {
                                cartBadge.style.display = 'flex';
                                
                                // Animation khi số lượng thay đổi
                                if (newCount !== oldCount) {
                                    cartBadge.classList.add('cart-updated');
                                    setTimeout(() => cartBadge.classList.remove('cart-updated'), 600);
                                }
                            } else {
                                cartBadge.style.display = 'none';
                            }
                        }
                    })
                    .catch(error => console.error('Error updating cart count:', error));
            @else
                // Nếu chưa đăng nhập, ẩn badge
                const cartBadge = document.getElementById('cart-count');
                if (cartBadge) {
                    cartBadge.style.display = 'none';
                }
            @endauth
        }

        // Cập nhật cart count khi load trang
        document.addEventListener('DOMContentLoaded', function() {
            updateCartCount();
        });
    </script>
    
    @yield('scripts')
</body>
</html>