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
    <div class="auth-bg" style="background: url('./images/background.png'); background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat;">
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
                            <a href="{{ route('dangnhap') }}" class="auth-tab active">ĐĂNG NHẬP</a>
                            <a href="{{ route('dangky') }}" class="auth-tab">TẠO TÀI KHOẢN</a>
                        </div>
                        
                        <!-- Login Form -->
                        <div class="auth-content">
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
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>