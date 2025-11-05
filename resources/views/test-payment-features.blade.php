@extends('layouts.app')

@section('title', 'Test Payment Features - WOW Box Shop')

@section('styles')
<style>
    body {
        background: linear-gradient(to bottom, #FFE135, #FFF7A0);
        min-height: 100vh;
    }

    .test-container {
        max-width: 1000px;
        margin: 50px auto;
        padding: 30px;
    }

    .test-header {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: center;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .test-header h1 {
        color: #004b00;
        margin-bottom: 15px;
    }

    .test-section {
        background: white;
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .section-title {
        color: #004b00;
        font-size: 1.5rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #004b00;
    }

    .feature-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .feature-card {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .feature-card:hover {
        border-color: #004b00;
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #004b00;
    }

    .feature-title {
        font-size: 1.2rem;
        font-weight: 500;
        color: #004b00;
        margin-bottom: 10px;
    }

    .feature-desc {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }

    .test-links {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 30px;
    }

    .link-btn {
        padding: 12px 25px;
        background: white;
        color: #004b00;
        border: 2px solid #004b00;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .link-btn:hover {
        background: #004b00;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .alert {
        padding: 15px;
        border-radius: 10px;
        margin: 15px 0;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>
@endsection

@section('content')
<div class="test-container">
    <div class="test-header">
        <h1><i class="fas fa-credit-card"></i> Test Hệ Thống Thanh Toán</h1>
        <p>Kiểm tra đầy đủ các tính năng thanh toán của WOW Box Shop</p>
        <div class="alert alert-success">
            ✅ Đã cập nhật: Tính giá với khuyến mãi, thêm COD và Demo Payment
        </div>
    </div>

    <!-- Tính năng thanh toán -->
    <div class="test-section">
        <h3 class="section-title">🎯 Các Tính Năng Mới</h3>
        
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="feature-title">Thanh Toán COD</div>
                <div class="feature-desc">
                    Thanh toán khi nhận hàng, hỗ trợ giao hàng tận nơi và nhận tại cửa hàng
                </div>
                <div class="alert alert-info">
                    <strong>✅ Hoạt động:</strong> Đặt hàng thành công → Chuyển thẳng đến trang thành công
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-code"></i></div>
                <div class="feature-title">Thanh Toán Demo</div>
                <div class="feature-desc">
                    Mô phỏng quá trình thanh toán với giao diện chuyên nghiệp
                </div>
                <div class="alert alert-info">
                    <strong>✅ Hoạt động:</strong> Trang demo với nút "Hoàn thành thanh toán" → Về trang chủ
                </div>
            </div>

            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
                <div class="feature-title">MoMo Payment (Thật)</div>
                <div class="feature-desc">
                    Tích hợp thật với MoMo Payment Gateway
                </div>
                <div class="alert alert-warning">
                    <strong>⚠️ Cần cấu hình:</strong> API keys MoMo Business để hoạt động
                </div>
            </div>
        </div>
    </div>

    <!-- Tính năng khuyến mãi -->
    <div class="test-section">
        <h3 class="section-title">🏷️ Hệ Thống Khuyến Mãi</h3>
        
        <div class="alert alert-success">
            <strong>✅ Đã cập nhật:</strong> Tính giá với khuyến mãi trong thanh toán
        </div>
        
        <ul style="text-align: left; margin: 20px 0;">
            <li>💰 Hiển thị giá gốc (gạch ngang) và giá khuyến mãi (màu đỏ)</li>
            <li>🏷️ Hiển thị badge khuyến mãi và số tiền tiết kiệm</li>
            <li>📊 Tổng kết tiết kiệm trong trang thanh toán</li>
            <li>🔄 Cập nhật real-time khi thay đổi số lượng</li>
        </ul>
    </div>

    <!-- Hướng dẫn test -->
    <div class="test-section">
        <h3 class="section-title">📋 Hướng Dẫn Test</h3>
        
        <div style="text-align: left;">
            <h4 style="color: #004b00; margin-bottom: 15px;">Bước 1: Chuẩn bị dữ liệu</h4>
            <ol>
                <li>Đăng nhập tài khoản</li>
                <li>Thêm sản phẩm có khuyến mãi vào giỏ hàng</li>
                <li>Kiểm tra hiển thị giá trong giỏ hàng</li>
            </ol>

            <h4 style="color: #004b00; margin: 20px 0 15px;">Bước 2: Test thanh toán</h4>
            <ol>
                <li><strong>Test COD:</strong> Chọn COD → Điền thông tin → Đặt hàng → Thành công</li>
                <li><strong>Test Demo:</strong> Chọn Demo → Điền thông tin → Trang demo → Hoàn thành</li>
                <li><strong>Test MoMo:</strong> Chọn MoMo → Chuyển đến MoMo (nếu đã cấu hình)</li>
            </ol>

            <h4 style="color: #004b00; margin: 20px 0 15px;">Bước 3: Kiểm tra kết quả</h4>
            <ol>
                <li>Trang thành công hiển thị đúng thông tin</li>
                <li>Giá tiền đã áp dụng khuyến mãi</li>
                <li>Nút "Về trang chủ" hoạt động</li>
            </ol>
        </div>
    </div>

    <!-- Test links -->
    <div class="test-section">
        <h3 class="section-title">🔗 Links Test</h3>
        
        <div class="test-links">
            <a href="{{ route('dat-mon.index') }}" class="link-btn">
                <i class="fas fa-utensils"></i> Trang Đặt Món
            </a>
            
            <a href="{{ route('giohang') }}" class="link-btn">
                <i class="fas fa-shopping-cart"></i> Xem Giỏ Hàng
            </a>
            
            <a href="{{ route('thanh-toan.index') }}" class="link-btn">
                <i class="fas fa-credit-card"></i> Trang Thanh Toán
            </a>
            
            <a href="{{ route('test-promotion-cart') }}" class="link-btn">
                <i class="fas fa-tags"></i> Test Khuyến Mãi
            </a>

            <a href="{{ route('test-checkout') }}" class="link-btn">
                <i class="fas fa-database"></i> Test Database
            </a>
        </div>
    </div>

    <!-- Thông tin kỹ thuật -->
    <div class="test-section">
        <h3 class="section-title">⚙️ Thông Tin Kỹ Thuật</h3>
        
        <div style="text-align: left;">
            <h4 style="color: #004b00;">Files đã cập nhật:</h4>
            <ul>
                <li><code>ThanhToanController.php</code> - Thêm logic tính giá khuyến mãi, COD, Demo</li>
                <li><code>thanh-toan/index.blade.php</code> - Thêm tùy chọn thanh toán, hiển thị giá khuyến mãi</li>
                <li><code>thanh-toan/demo.blade.php</code> - Trang thanh toán demo mới</li>
                <li><code>thanh-toan/success.blade.php</code> - Cập nhật nút về trang chủ</li>
                <li><code>routes/web.php</code> - Thêm routes demo</li>
            </ul>

            <h4 style="color: #004b00; margin-top: 20px;">Phương thức thanh toán:</h4>
            <ul>
                <li><strong>COD:</strong> <code>cod</code> - Thanh toán khi nhận hàng</li>
                <li><strong>Demo:</strong> <code>demo</code> - Thanh toán mô phỏng</li>
                <li><strong>MoMo:</strong> <code>momo</code> - Thanh toán MoMo thật</li>
            </ul>
        </div>
    </div>
</div>
@endsection