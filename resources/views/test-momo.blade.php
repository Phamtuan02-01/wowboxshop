@extends('layouts.app')

@section('title', 'Test MoMo Payment - WOW Box Shop')

@section('styles')
<style>
    .test-container {
        max-width: 600px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .test-form {
        margin: 20px 0;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #004b00;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
    }

    .btn-test {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #004b00, #006600);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-test:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 0, 0.3);
    }

    .result {
        margin-top: 20px;
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

    .test-info {
        background: #e7f3ff;
        border: 1px solid #b3d9ff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
</style>
@endsection

@section('content')
<div class="test-container">
    <h2 style="text-align: center; color: #004b00; margin-bottom: 20px;">Test MoMo Payment Integration</h2>
    
    <div class="test-info">
        <h4>Thông Tin Test:</h4>
        <ul>
            <li><strong>Partner Code:</strong> {{ config('services.momo.partner_code') }}</li>
            <li><strong>Access Key:</strong> {{ config('services.momo.access_key') }}</li>
            <li><strong>Endpoint:</strong> {{ config('services.momo.endpoint') }}</li>
            <li><strong>Return URL:</strong> {{ config('services.momo.return_url') }}</li>
        </ul>
    </div>

    <form class="test-form" id="testForm">
        @csrf
        
        <div class="form-group">
            <label for="orderId">Order ID:</label>
            <input type="text" class="form-control" id="orderId" name="orderId" 
                   value="TEST{{ date('YmdHis') }}" required>
        </div>
        
        <div class="form-group">
            <label for="amount">Số tiền (VNĐ):</label>
            <input type="number" class="form-control" id="amount" name="amount" 
                   value="50000" min="10000" max="50000000" required>
        </div>
        
        <div class="form-group">
            <label for="orderInfo">Thông tin đơn hàng:</label>
            <input type="text" class="form-control" id="orderInfo" name="orderInfo" 
                   value="Test thanh toán MoMo - WOW Box Shop" required>
        </div>
        
        <button type="submit" class="btn-test" id="submitBtn">
            <i class="fas fa-credit-card"></i> Tạo Thanh Toán MoMo
        </button>
    </form>
    
    <div class="result" id="result"></div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('testForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const result = document.getElementById('result');
    
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang tạo thanh toán...';
    
    const formData = new FormData(this);
    
    fetch('/test-momo-payment', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        result.style.display = 'block';
        
        if (data.success) {
            result.className = 'result success';
            result.innerHTML = `
                <h4>✅ Tạo thanh toán thành công!</h4>
                <p><strong>Order ID:</strong> ${data.orderId}</p>
                <p><strong>Request ID:</strong> ${data.requestId}</p>
                <p><strong>Pay URL:</strong></p>
                <p><a href="${data.payUrl}" target="_blank" style="word-break: break-all;">${data.payUrl}</a></p>
                <br>
                <a href="${data.payUrl}" class="btn-test" style="display: inline-block; text-decoration: none; text-align: center;">
                    🚀 Mở Trang Thanh Toán MoMo
                </a>
            `;
        } else {
            result.className = 'result error';
            result.innerHTML = `
                <h4>❌ Có lỗi xảy ra!</h4>
                <p><strong>Lỗi:</strong> ${data.message}</p>
                ${data.error ? `<p><strong>Chi tiết:</strong> ${data.error}</p>` : ''}
            `;
        }
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-credit-card"></i> Tạo Thanh Toán MoMo';
    })
    .catch(error => {
        result.style.display = 'block';
        result.className = 'result error';
        result.innerHTML = `
            <h4>❌ Có lỗi xảy ra!</h4>
            <p>Không thể kết nối đến server. Vui lòng thử lại.</p>
        `;
        
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="fas fa-credit-card"></i> Tạo Thanh Toán MoMo';
    });
});
</script>
@endsection