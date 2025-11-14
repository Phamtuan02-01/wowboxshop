<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - WowBox Shop</title>
    
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
                            <a href="{{ route('dangnhap') }}" class="auth-tab">ĐĂNG NHẬP</a>
                            <a href="{{ route('dangky') }}" class="auth-tab active">TẠO TÀI KHOẢN</a>
                        </div>
                        
                        <!-- Register Form -->
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

                            <form method="POST" action="{{ route('dangky') }}">
                                @csrf
                                
                                <!-- Tên đăng nhập -->
                                <div class="form-group">
                                    <label for="ten_dang_nhap" class="form-label">Tên đăng nhập*</label>
                                    <input type="text" 
                                           class="form-control @error('ten_dang_nhap') is-invalid @enderror" 
                                           id="ten_dang_nhap" 
                                           name="ten_dang_nhap" 
                                           value="{{ old('ten_dang_nhap') }}" 
                                           required 
                                           maxlength="50"
                                           placeholder="Ví dụ: nguyenvana, hoangthib...">
                                    @error('ten_dang_nhap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Họ tên -->
                                <div class="form-group">
                                    <label for="ho_ten" class="form-label">Họ và tên*</label>
                                    <input type="text" 
                                           class="form-control @error('ho_ten') is-invalid @enderror" 
                                           id="ho_ten" 
                                           name="ho_ten" 
                                           value="{{ old('ho_ten') }}" 
                                           required 
                                           maxlength="100"
                                           placeholder="Ví dụ: Nguyễn Văn A">
                                    @error('ho_ten')
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
                                           placeholder="example@gmail.com">
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
                                           placeholder="Ví dụ: 0987654321">
                                    @error('so_dien_thoai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Mật khẩu -->
                                <div class="form-group">
                                    <label for="mat_khau" class="form-label">Mật khẩu*</label>
                                    <input type="password" 
                                           class="form-control @error('mat_khau') is-invalid @enderror" 
                                           id="mat_khau" 
                                           name="mat_khau" 
                                           required
                                           minlength="6"
                                           placeholder="••••••">
                                    <small class="form-text text-muted">Mật khẩu phải có ít nhất 6 ký tự</small>
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
                                    <small class="form-text text-muted">Nhập lại mật khẩu để xác nhận</small>
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
    
    <script>
        // Tự động format họ tên - viết hoa chữ cái đầu (không làm gì cả, để người dùng tự nhập)
        // Bỏ auto-format để tránh lỗi với chữ có dấu
        document.getElementById('ho_ten').addEventListener('input', function(e) {
            // Không format tự động nữa, để người dùng tự nhập đúng
        });

        // Validation số điện thoại chỉ cho phép số
        document.getElementById('so_dien_thoai').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, ''); // Chỉ giữ lại số
            if (value.length > 0 && !value.startsWith('0')) {
                value = '0' + value;
            }
            e.target.value = value;
        });

        // Kiểm tra mật khẩu khớp nhau
        document.getElementById('mat_khau_confirmation').addEventListener('input', function(e) {
            const password = document.getElementById('mat_khau').value;
            const confirmPassword = e.target.value;
            
            if (confirmPassword.length > 0) {
                if (password !== confirmPassword) {
                    e.target.setCustomValidity('Mật khẩu xác nhận không khớp');
                    e.target.classList.add('is-invalid');
                } else {
                    e.target.setCustomValidity('');
                    e.target.classList.remove('is-invalid');
                    e.target.classList.add('is-valid');
                }
            }
        });

        // Kiểm tra độ mạnh mật khẩu
        document.getElementById('mat_khau').addEventListener('input', function(e) {
            const password = e.target.value;
            const confirmPassword = document.getElementById('mat_khau_confirmation');
            
            // Reset confirmation password validation
            if (confirmPassword.value.length > 0) {
                if (password !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Mật khẩu xác nhận không khớp');
                    confirmPassword.classList.add('is-invalid');
                } else {
                    confirmPassword.setCustomValidity('');
                    confirmPassword.classList.remove('is-invalid');
                    confirmPassword.classList.add('is-valid');
                }
            }
        });

        // Validate form trước khi submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = ['ten_dang_nhap', 'ho_ten', 'email', 'so_dien_thoai', 'mat_khau', 'mat_khau_confirmation'];
            let isValid = true;

            requiredFields.forEach(fieldName => {
                const field = document.getElementById(fieldName);
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Kiểm tra checkbox điều khoản
            const agreeTerms = document.getElementById('agree_terms');
            if (!agreeTerms.checked) {
                alert('Vui lòng đồng ý với điều khoản dịch vụ');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>