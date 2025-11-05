@extends('layouts.app')

@section('title', 'Test Checkout Flow - WOW Box Shop')

@section('styles')
<style>
    .test-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .test-step {
        margin: 30px 0;
        padding: 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
    }
    
    .test-step h3 {
        color: #004b00;
        margin-bottom: 15px;
    }
    
    .btn-test {
        padding: 12px 25px;
        background: linear-gradient(135deg, #004b00, #006600);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 5px;
    }
    
    .btn-test:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0, 75, 0, 0.3);
    }
    
    .result {
        margin-top: 15px;
        padding: 15px;
        border-radius: 8px;
        display: none;
    }
    
    .result.success {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }
    
    .result.error {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }
</style>
@endsection

@section('content')
<div class="test-container">
    <h2 style="text-align: center; color: #004b00; margin-bottom: 30px;">Test Checkout Complete Flow</h2>
    
    <!-- Step 1: Check Login -->
    <div class="test-step">
        <h3>Bước 1: Kiểm tra đăng nhập</h3>
        <p>Status: 
            @auth
                <span style="color: #28a745;">✅ Đã đăng nhập ({{ Auth::user()->ten_dang_nhap }})</span>
            @else
                <span style="color: #dc3545;">❌ Chưa đăng nhập</span>
                <a href="{{ route('dangnhap') }}" class="btn-test">Đăng nhập</a>
            @endif
        </p>
    </div>
    
    <!-- Step 2: Check Cart -->
    <div class="test-step">
        <h3>Bước 2: Kiểm tra giỏ hàng</h3>
        @auth
            @php
                $gioHang = Auth::user()->gioHang;
                $tongSoLuong = $gioHang ? $gioHang->chiTietGioHangs->sum('so_luong') : 0;
            @endphp
            <p>Giỏ hàng: {{ $tongSoLuong }} sản phẩm</p>
            
            @if($tongSoLuong > 0)
                <button class="btn-test" onclick="goToCheckout()">Đi đến thanh toán</button>
            @else
                <a href="{{ route('dat-mon.index') }}" class="btn-test">Thêm sản phẩm vào giỏ</a>
            @endif
        @else
            <p>Vui lòng đăng nhập để xem giỏ hàng</p>
        @endauth
    </div>
    
    <!-- Step 3: Test MoMo Integration -->
    <div class="test-step">
        <h3>Bước 3: Test MoMo Integration</h3>
        <button class="btn-test" onclick="testMoMo()">Test MoMo Payment</button>
        <div class="result" id="momo-result"></div>
    </div>
    
    <!-- Step 4: Debug Info -->
    <div class="test-step">
        <h3>Bước 4: Debug Information</h3>
        <p><strong>Current Time:</strong> {{ now() }}</p>
        <p><strong>Environment:</strong> {{ config('app.env') }}</p>
        <p><strong>MoMo Config:</strong></p>
        <ul>
            <li>Partner Code: {{ config('services.momo.partner_code') }}</li>
            <li>Access Key: {{ config('services.momo.access_key') }}</li>
            <li>Endpoint: {{ config('services.momo.endpoint') }}</li>
        </ul>
        
        <button class="btn-test" onclick="checkDatabase()">Check Database</button>
        <div class="result" id="db-result"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function goToCheckout() {
    window.location.href = '{{ route("thanh-toan.index") }}';
}

function testMoMo() {
    const result = document.getElementById('momo-result');
    result.style.display = 'block';
    result.className = 'result';
    result.innerHTML = '🔄 Đang test MoMo integration...';
    
    fetch('/test-momo-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            orderId: 'TEST' + Date.now(),
            amount: 50000,
            orderInfo: 'Test MoMo Payment - WOW Box Shop'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            result.className = 'result success';
            result.innerHTML = `
                ✅ <strong>MoMo Test thành công!</strong><br>
                - Order ID: ${data.orderId}<br>
                - Request ID: ${data.requestId}<br>
                <a href="${data.payUrl}" target="_blank" class="btn-test">Mở MoMo Payment</a>
            `;
        } else {
            result.className = 'result error';
            result.innerHTML = `❌ <strong>MoMo Test thất bại:</strong><br>${data.message}`;
        }
    })
    .catch(error => {
        result.className = 'result error';
        result.innerHTML = '❌ <strong>Lỗi kết nối:</strong><br>' + error.message;
    });
}

function checkDatabase() {
    const result = document.getElementById('db-result');
    result.style.display = 'block';
    result.className = 'result';
    result.innerHTML = '🔄 Đang kiểm tra database...';
    
    fetch('/check-database', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            result.className = 'result success';
            result.innerHTML = `
                ✅ <strong>Database OK!</strong><br>
                - Sản phẩm: ${data.products} items<br>
                - Biến thể: ${data.variants} items<br>
                - Đơn hàng: ${data.orders} items<br>
                - Giỏ hàng: ${data.carts} items
            `;
        } else {
            result.className = 'result error';
            result.innerHTML = `❌ <strong>Database Error:</strong><br>${data.message}`;
        }
    })
    .catch(error => {
        result.className = 'result error';
        result.innerHTML = '❌ <strong>Lỗi kết nối:</strong><br>' + error.message;
    });
}
</script>
@endsection