@extends('layouts.app')

@section('title', 'Thanh Toán Demo - WOW Box Shop')

@section('styles')
<style>
    body {
        background: linear-gradient(to bottom, #FFE135, #FFF7A0);
        min-height: 100vh;
    }

    .demo-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    .demo-header {
        margin-bottom: 30px;
    }

    .demo-header h1 {
        color: #007bff;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .demo-badge {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
        display: inline-block;
        margin-bottom: 20px;
    }

    .payment-simulator {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin: 30px 0;
        border: 2px dashed #007bff;
    }

    .payment-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .info-item {
        background: white;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #007bff;
    }

    .info-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
    }

    .order-summary {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
        border: 1px solid #e0e0e0;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-item:last-child {
        border-bottom: none;
        font-weight: bold;
        color: #007bff;
        font-size: 1.2rem;
    }

    .payment-status {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        padding: 20px;
        border-radius: 15px;
        margin: 20px 0;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .demo-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }

    .btn {
        padding: 12px 30px;
        border-radius: 25px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-outline-primary {
        background: white;
        color: #007bff;
        border: 2px solid #007bff;
    }

    .btn-outline-primary:hover {
        background: #007bff;
        color: white;
        text-decoration: none;
    }

    .simulation-steps {
        text-align: left;
        margin: 20px 0;
    }

    .step {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px 0;
        font-size: 0.95rem;
        color: #666;
    }

    .step-icon {
        width: 30px;
        height: 30px;
        background: #28a745;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .demo-note {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 15px;
        margin: 20px 0;
        font-size: 0.9rem;
        color: #856404;
    }

    @media (max-width: 768px) {
        .demo-container {
            margin: 20px;
            padding: 20px;
        }

        .payment-info {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .demo-buttons {
            flex-direction: column;
        }

        .demo-header h1 {
            font-size: 2rem;
        }
    }
</style>
@endsection

@section('content')
<div class="demo-container">
    <div class="demo-header">
        <div class="demo-badge">
            <i class="fas fa-code"></i> DEMO MODE
        </div>
        <h1><i class="fas fa-credit-card"></i> Thanh Toán Demo</h1>
        <p>Mô phỏng quá trình thanh toán cho mục đích demo hệ thống</p>
    </div>

    <div class="payment-info">
        <div class="info-item">
            <div class="info-label">Mã đơn hàng</div>
            <div class="info-value">#{{ $donHang->ma_don_hang }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Tổng tiền</div>
            <div class="info-value">{{ number_format($donHang->tong_tien) }}₫</div>
        </div>
        <div class="info-item">
            <div class="info-label">Khách hàng</div>
            <div class="info-value">{{ $donHang->ho_ten ?? Auth::user()->ho_ten }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Phương thức</div>
            <div class="info-value">Thanh toán Demo</div>
        </div>
    </div>

    <div class="order-summary">
        <h4 style="color: #007bff; margin-bottom: 15px;">
            <i class="fas fa-shopping-cart"></i> Chi tiết đơn hàng
        </h4>
        @foreach($donHang->chiTietDonHangs as $chiTiet)
        <div class="order-item">
            <div>
                <strong>{{ $chiTiet->sanPham->ten_san_pham }}</strong>
                <br>
                <small>{{ $chiTiet->bienThe->kich_thuoc ?? 'Mặc định' }} × {{ $chiTiet->so_luong }}</small>
            </div>
            <div>{{ number_format($chiTiet->gia_tai_thoi_diem_mua * $chiTiet->so_luong) }}₫</div>
        </div>
        @endforeach
        @if($donHang->phuong_thuc_giao_hang == 'giao_hang')
        <div class="order-item">
            <div>Phí giao hàng</div>
            <div>10,000₫</div>
        </div>
        @endif
        <div class="order-item">
            <div>Tổng cộng</div>
            <div>{{ number_format($donHang->tong_tien) }}₫</div>
        </div>
    </div>

    <div class="payment-simulator">
        <h4 style="color: #007bff; margin-bottom: 20px;">
            <i class="fas fa-desktop"></i> Mô phỏng thanh toán
        </h4>
        
        <div class="simulation-steps">
            <div class="step">
                <div class="step-icon"><i class="fas fa-check"></i></div>
                <div>Kết nối với cổng thanh toán demo</div>
            </div>
            <div class="step">
                <div class="step-icon"><i class="fas fa-check"></i></div>
                <div>Xác thực thông tin đơn hàng</div>
            </div>
            <div class="step">
                <div class="step-icon"><i class="fas fa-check"></i></div>
                <div>Tạo phiên giao dịch demo</div>
            </div>
            <div class="step">
                <div class="step-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <div>Chờ xác nhận thanh toán...</div>
            </div>
        </div>

        <div class="payment-status">
            <i class="fas fa-clock"></i> Sẵn sàng xử lý thanh toán demo
        </div>
    </div>

    <div class="demo-note">
        <i class="fas fa-info-circle"></i>
        <strong>Lưu ý:</strong> Đây là chế độ demo, không có giao dịch tiền thật nào được thực hiện. 
        Bạn có thể bấm "Hoàn thành thanh toán" để mô phỏng thanh toán thành công.
    </div>

    <div class="demo-buttons">
        <form method="POST" action="{{ route('thanh-toan.demo-complete', $donHang->ma_don_hang) }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-success">
                <i class="fas fa-check-circle"></i> Hoàn thành thanh toán
            </button>
        </form>
        
        <a href="{{ route('trangchu') }}" class="btn btn-outline-primary">
            <i class="fas fa-home"></i> Về trang chủ
        </a>
    </div>

    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e0e0e0; color: #666; font-size: 0.9rem;">
        <i class="fas fa-shield-alt"></i> Demo an toàn - Không có giao dịch thật
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto redirect after 30 seconds if no action
    setTimeout(function() {
        if (confirm('Bạn có muốn tự động hoàn thành thanh toán demo không?')) {
            document.querySelector('form').submit();
        }
    }, 30000);

    // Add some demo effects
    document.addEventListener('DOMContentLoaded', function() {
        // Simulate loading effect
        setTimeout(function() {
            const spinner = document.querySelector('.fa-spinner');
            if (spinner) {
                spinner.classList.remove('fa-spinner', 'fa-spin');
                spinner.classList.add('fa-check');
                
                const stepText = spinner.closest('.step').querySelector('div:last-child');
                stepText.textContent = 'Sẵn sàng xử lý thanh toán';
            }
        }, 2000);
    });
</script>
@endsection