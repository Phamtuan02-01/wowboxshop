<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - WowBox Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-bg">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <!-- Welcome Section -->
                <div class="col-lg-6">
                    <div class="welcome-section">
                        <h1>CHÀO MỪNG<br>BẠN ĐẾN VỚI<br>WOWBOX</h1>
                    </div>
                </div>
                
                <!-- Auth Container -->
                <div class="col-lg-5 offset-lg-1">
                    <div class="auth-container">
                        <!-- Tabs -->
                        <div class="auth-tabs">
                            <a href="#" class="auth-tab active" data-tab="login">ĐĂNG NHẬP</a>
                            <a href="#" class="auth-tab" data-tab="register">TẠO TÀI KHOẢN</a>
                        </div>
                        
                        <!-- Login Form -->
                        <div class="auth-content" id="login-form">
                            <!-- Flash Messages -->
                            @if(session('success'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('dangnhap') }}">
                                @csrf
                                
                                <!-- Tên đăng nhập -->
                                <div class="form-group">
                                    <label for="ten_dang_nhap" class="form-label">Tên đăng nhập</label>
                                    <input type="text" 
                                           class="form-control @error('ten_dang_nhap') is-invalid @enderror" 
                                           id="ten_dang_nhap" 
                                           name="ten_dang_nhap" 
                                           value="{{ old('ten_dang_nhap') }}" 
                                           required 
                                           placeholder="Nhập tên đăng nhập của bạn vào đây">
                                    @error('ten_dang_nhap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mật khẩu -->
                                <div class="form-group">
                                    <label for="mat_khau" class="form-label">Mật khẩu</label>
                                    <input type="password" 
                                           class="form-control @error('mat_khau') is-invalid @enderror" 
                                           id="mat_khau" 
                                           name="mat_khau" 
                                           required
                                           placeholder="••••••">
                                    @error('mat_khau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Ghi nhớ mật khẩu</label>
                                    <a href="#" class="forgot-password">Quên mật khẩu?</a>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn-submit">
                                    ĐĂNG NHẬP
                                </button>
                            </form>
                        </div>

                        <!-- Register Form -->
                        <div class="auth-content" id="register-form" style="display: none;">
                            <form method="POST" action="{{ route('dangky') }}">
                                @csrf
                                
                                <!-- Tên đăng nhập -->
                                <div class="form-group">
                                    <label for="reg_ten_dang_nhap" class="form-label">Tên đăng nhập*</label>
                                    <input type="text" 
                                           class="form-control @error('ten_dang_nhap') is-invalid @enderror" 
                                           id="reg_ten_dang_nhap" 
                                           name="ten_dang_nhap" 
                                           value="{{ old('ten_dang_nhap') }}" 
                                           required 
                                           maxlength="50"
                                           placeholder="Nhập tên đăng nhập của bạn vào đây">
                                    @error('ten_dang_nhap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="email" class="form-label">Email*</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required
                                           maxlength="100"
                                           placeholder="Nhập email của bạn">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Số điện thoại -->
                                <div class="form-group">
                                    <label for="so_dien_thoai" class="form-label">Số điện thoại*</label>
                                    <input type="tel" 
                                           class="form-control @error('so_dien_thoai') is-invalid @enderror" 
                                           id="so_dien_thoai" 
                                           name="so_dien_thoai" 
                                           value="{{ old('so_dien_thoai') }}" 
                                           required
                                           maxlength="20"
                                           placeholder="Nhập số điện thoại đặt hàng">
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mật khẩu -->
                                <div class="form-group">
                                    <label for="reg_mat_khau" class="form-label">Mật khẩu*</label>
                                    <input type="password" 
                                           class="form-control @error('mat_khau') is-invalid @enderror" 
                                           id="reg_mat_khau" 
                                           name="mat_khau" 
                                           required
                                           minlength="6"
                                           placeholder="••••••">
                                    @error('mat_khau')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Xác nhận mật khẩu -->
                                <div class="form-group">
                                    <label for="mat_khau_confirmation" class="form-label">Nhập lại mật khẩu*</label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="mat_khau_confirmation" 
                                           name="mat_khau_confirmation" 
                                           required
                                           minlength="6"
                                           placeholder="••••••">
                                </div>

                                <!-- Terms -->
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="agree_terms" required>
                                    <label class="form-check-label" for="agree_terms">
                                        Tôi đồng ý điều khoản dịch vụ
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn-submit">
                                    ĐĂNG KÝ
                                </button>
                            </form>

                            <!-- Footer Text -->
                            <div class="auth-footer">
                                <p>Sau khi nhập mã ĐĂNG KÝ để hoàn tất</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Tab Switching Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.auth-tab');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Show/hide forms
                    if (this.dataset.tab === 'login') {
                        loginForm.style.display = 'block';
                        registerForm.style.display = 'none';
                    } else {
                        loginForm.style.display = 'none';
                        registerForm.style.display = 'block';
                    }
                });
            });
        });
    </script>
</body>
</html>