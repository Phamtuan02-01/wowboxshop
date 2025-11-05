@extends('layouts.app')

@section('title', 'Đặt Hàng Thành Công - WOW Box Shop')

@section('styles')
<style>
    body {
        background: linear-gradient(to bottom, #FFE135, #FFF7A0);
        min-height: 100vh;
    }

    .success-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 50px 20px;
        text-align: center;
    }

    .success-card {
        background: white;
        border-radius: 25px;
        padding: 50px 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .success-icon {
        font-size: 5rem;
        color: #00b894;
        margin-bottom: 30px;
        animation: checkmark 0.8s ease-in-out;
    }

    @keyframes checkmark {
        0% { transform: scale(0) rotate(0deg); }
        50% { transform: scale(1.2) rotate(180deg); }
        100% { transform: scale(1) rotate(360deg); }
    }

    .success-title {
        color: #004b00;
        font-size: 2.5rem;
        font-weight: 300;
        margin-bottom: 15px;
    }

    .success-subtitle {
        color: #666;
        font-size: 1.2rem;
        margin-bottom: 40px;
    }

    .order-info {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        text-align: left;
    }

    .order-info h3 {
        color: #004b00;
        font-size: 1.5rem;
        margin-bottom: 20px;
        text-align: center;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #004b00;
        font-weight: 600;
    }

    .order-code {
        background: #004b00;
        color: white;
        padding: 8px 15px;
        border-radius: 20px;
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
    }

    .status-badge {
        background: #00b894;
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.9rem;
    }

    .order-items {
        margin: 30px 0;
    }

    .order-items h4 {
        color: #004b00;
        margin-bottom: 20px;
        text-align: center;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        background: white;
        border-radius: 10px;
        margin-bottom: 10px;
        border: 1px solid #e9ecef;
    }

    .item-image {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        object-fit: cover;
        border: 2px solid #004b00;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 500;
        color: #004b00;
        margin-bottom: 5px;
    }

    .item-details {
        font-size: 0.9rem;
        color: #666;
    }

    .item-price {
        font-weight: 600;
        color: #004b00;
    }

    .delivery-info {
        background: #e8f5e8;
        border: 2px solid #004b00;
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
        text-align: left;
    }

    .delivery-info h4 {
        color: #004b00;
        margin-bottom: 15px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .delivery-method {
        font-size: 1.1rem;
        font-weight: 500;
        color: #004b00;
        margin-bottom: 10px;
    }

    .delivery-address {
        color: #666;
        line-height: 1.6;
    }

    .store-info {
        background: white;
        border-radius: 10px;
        padding: 15px;
        margin-top: 15px;
        border: 1px solid #004b00;
    }

    .actions {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 40px;
    }

    .btn {
        padding: 15px 30px;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary {
        background: linear-gradient(135deg, #004b00, #006600);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 0, 0.3);
        color: white;
        text-decoration: none;
    }

    .btn-outline {
        background: white;
        color: #004b00;
        border: 2px solid #004b00;
    }

    .btn-outline:hover {
        background: #004b00;
        color: white;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .timeline {
        margin: 30px 0;
        text-align: left;
    }

    .timeline h4 {
        color: #004b00;
        margin-bottom: 20px;
        text-align: center;
    }

    .timeline-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
    }

    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        color: white;
        flex-shrink: 0;
    }

    .timeline-icon.completed {
        background: #00b894;
    }

    .timeline-icon.current {
        background: #fdcb6e;
        animation: pulse 2s infinite;
    }

    .timeline-icon.pending {
        background: #ddd;
        color: #999;
    }

    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(253, 203, 110, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(253, 203, 110, 0); }
        100% { box-shadow: 0 0 0 0 rgba(253, 203, 110, 0); }
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        font-weight: 500;
        color: #004b00;
        margin-bottom: 5px;
    }

    .timeline-desc {
        font-size: 0.9rem;
        color: #666;
    }

    .payment-info {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 15px;
        padding: 20px;
        margin: 20px 0;
    }

    .payment-info h4 {
        color: #d63031;
        margin-bottom: 15px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .success-container {
            padding: 30px 15px;
        }

        .success-card {
            padding: 30px 20px;
        }

        .success-title {
            font-size: 2rem;
        }

        .success-subtitle {
            font-size: 1rem;
        }

        .info-row {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }

        .order-item {
            flex-direction: column;
            text-align: center;
        }

        .actions {
            flex-direction: column;
            align-items: center;
        }

        .btn {
            width: 100%;
            justify-content: center;
            max-width: 300px;
        }
    }
</style>
@endsection

@section('content')
<div class="success-container">
    <div class="success-card">
        <div class="success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h1 class="success-title">Đặt Hàng Thành Công!</h1>
        <p class="success-subtitle">Cảm ơn bạn đã tin tướng và đặt hàng tại WOW Box Shop</p>
        
        <div class="order-info">
            <h3>Thông Tin Đơn Hàng</h3>
            
            <div class="info-row">
                <span class="info-label">Mã đơn hàng:</span>
                <span class="info-value order-code">{{ $donHang->ma_don_hang }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Ngày đặt:</span>
                <span class="info-value">{{ $donHang->ngay_tao->format('d/m/Y H:i') }}</span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Trạng thái:</span>
                <span class="info-value">
                    <span class="status-badge">
                        @if($donHang->trang_thai === 'da_thanh_toan')
                            Đã thanh toán
                        @elseif($donHang->trang_thai === 'cho_xac_nhan')
                            Chờ xác nhận
                        @else
                            {{ ucfirst(str_replace('_', ' ', $donHang->trang_thai)) }}
                        @endif
                    </span>
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Phương thức thanh toán:</span>
                <span class="info-value">
                    @if($donHang->phuong_thuc_thanh_toan === 'momo')
                        <i class="fas fa-mobile-alt"></i> MoMo
                    @elseif($donHang->phuong_thuc_thanh_toan === 'cod')
                        <i class="fas fa-money-bill-wave"></i> COD (Thanh toán khi nhận hàng)
                    @elseif($donHang->phuong_thuc_thanh_toan === 'demo')
                        <i class="fas fa-code"></i> Demo Payment
                    @endif
                </span>
            </div>
            
            <div class="info-row">
                <span class="info-label">Tổng tiền:</span>
                <span class="info-value" style="font-size: 1.2rem;">
                    {{ number_format($donHang->tong_tien, 0, ',', '.') }}₫
                </span>
            </div>
        </div>
        
        <!-- Thông tin giao hàng -->
        <div class="delivery-info">
            <h4>
                @if($donHang->phuong_thuc_giao_hang === 'giao_hang')
                    <i class="fas fa-truck"></i> Giao Hàng Tận Nơi
                @else
                    <i class="fas fa-store"></i> Nhận Tại Cửa Hàng
                @endif
            </h4>
            
            @if($donHang->phuong_thuc_giao_hang === 'giao_hang')
                <div class="delivery-method">Giao hàng tận nơi</div>
                <div class="delivery-address">
                    <strong>{{ $donHang->ho_ten }}</strong><br>
                    {{ $donHang->so_dien_thoai }}<br>
                    {{ $donHang->dia_chi }}<br>
                    {{ $donHang->tinh_thanh_pho }}
                </div>
                
                <div class="timeline">
                    <h4>Tiến Trình Giao Hàng</h4>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon {{ in_array($donHang->trang_thai, ['cho_xac_nhan', 'da_thanh_toan', 'da_giao']) ? 'completed' : 'current' }}">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Đang chờ xác nhận</div>
                            <div class="timeline-desc">
                                {{ $donHang->ngay_tao->format('d/m/Y H:i') }}
                                @if(in_array($donHang->trang_thai, ['cho_xac_nhan', 'da_thanh_toan']))
                                    <br><strong style="color: #fdcb6e;">Đơn hàng đang được xác nhận</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $donHang->trang_thai === 'da_giao' ? 'completed' : 'pending' }}">
                            <i class="fas fa-home"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Đã giao hàng</div>
                            <div class="timeline-desc">
                                @if($donHang->trang_thai === 'da_giao')
                                    Đơn hàng đã được giao thành công
                                @else
                                    Chúng tôi sẽ giao hàng sau khi xác nhận đơn
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="delivery-method">Nhận tại cửa hàng</div>
                <div class="store-info">
                    <strong>{{ $donHang->cua_hang_nhan }}</strong><br>
                    <div class="delivery-address">
                        Vui lòng đến cửa hàng để nhận đơn hàng của bạn.<br>
                        <strong>Thời gian chuẩn bị:</strong> 30-60 phút<br>
                        <strong>Giờ mở cửa:</strong> 8:00 - 22:00 hàng ngày
                    </div>
                </div>
                
                <div class="timeline">
                    <h4>Tiến Trình Chuẩn Bị</h4>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon {{ in_array($donHang->trang_thai, ['cho_xac_nhan', 'da_thanh_toan', 'da_giao']) ? 'completed' : 'current' }}">
                            <i class="fas fa-check"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Đang chờ xác nhận</div>
                            <div class="timeline-desc">
                                {{ $donHang->ngay_tao->format('d/m/Y H:i') }}
                                @if(in_array($donHang->trang_thai, ['cho_xac_nhan', 'da_thanh_toan']))
                                    <br><strong style="color: #fdcb6e;">Đơn hàng đang được xác nhận và chuẩn bị</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $donHang->trang_thai === 'da_giao' ? 'completed' : 'pending' }}">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Đã giao hàng / Sẵn sàng nhận</div>
                            <div class="timeline-desc">
                                @if($donHang->trang_thai === 'da_giao')
                                    Đơn hàng đã sẵn sàng để bạn nhận
                                @else
                                    Chúng tôi sẽ thông báo khi đơn hàng sẵn sàng
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Chi tiết sản phẩm -->
        <div class="order-items">
            <h4>Chi Tiết Đơn Hàng</h4>
            
            @php
                $tongSanPham = 0;
            @endphp
            @foreach($donHang->chiTietDonHangs as $chiTiet)
            @php
                $tongSanPham += $chiTiet->gia_tai_thoi_diem_mua * $chiTiet->so_luong;
            @endphp
            <div class="order-item">
                <img src="{{ $chiTiet->sanPham && $chiTiet->sanPham->hinh_anh ? asset('images/products/' . $chiTiet->sanPham->hinh_anh) : asset('images/default-product.png') }}" 
                     alt="{{ $chiTiet->sanPham ? $chiTiet->sanPham->ten_san_pham : 'Sản phẩm' }}" class="item-image"
                     onerror="this.src='{{ asset('images/default-product.png') }}'">
                <div class="item-info">
                    <div class="item-name">{{ $chiTiet->sanPham->ten_san_pham }}</div>
                    <div class="item-details">
                        Kích thước: {{ $chiTiet->bienThe->kich_thuoc ?? 'Mặc định' }} |
                        Số lượng: {{ $chiTiet->so_luong }} |
                        Đơn giá: {{ number_format($chiTiet->gia_tai_thoi_diem_mua, 0, ',', '.') }}₫
                    </div>
                </div>
                <div class="item-price">
                    {{ number_format($chiTiet->gia_tai_thoi_diem_mua * $chiTiet->so_luong, 0, ',', '.') }}₫
                </div>
            </div>
            @endforeach
            
            <!-- Tổng kết đơn hàng -->
            <div style="margin-top: 20px; padding-top: 20px; border-top: 2px solid #004b00;">
                <div class="info-row">
                    <span class="info-label">Tổng tiền sản phẩm:</span>
                    <span class="info-value">{{ number_format($tongSanPham, 0, ',', '.') }}₫</span>
                </div>
                
                @if($donHang->giam_gia_khuyen_mai > 0)
                <div class="info-row" style="color: #28a745;">
                    <span class="info-label"><i class="fas fa-ticket-alt"></i> Giảm giá khuyến mãi:</span>
                    <span class="info-value">-{{ number_format($donHang->giam_gia_khuyen_mai, 0, ',', '.') }}₫</span>
                </div>
                @endif
                
                @if($donHang->phuong_thuc_giao_hang === 'giao_hang')
                <div class="info-row">
                    <span class="info-label">Phí giao hàng:</span>
                    <span class="info-value">{{ number_format(10000, 0, ',', '.') }}₫</span>
                </div>
                @endif
                
                <div class="info-row" style="font-size: 1.2rem; font-weight: bold; border-top: 2px solid #004b00; padding-top: 15px; margin-top: 10px;">
                    <span class="info-label">Tổng thanh toán:</span>
                    <span class="info-value" style="color: #dc3545;">{{ number_format($donHang->tong_tien, 0, ',', '.') }}₫</span>
                </div>
            </div>
        </div>
        
        @if($donHang->phuong_thuc_thanh_toan === 'momo' && $donHang->trang_thai === 'cho_thanh_toan')
        <div class="payment-info">
            <h4><i class="fas fa-exclamation-triangle"></i> Lưu Ý Thanh Toán</h4>
            <p>Đơn hàng của bạn đã được xác nhận nhưng chưa hoàn thành thanh toán. 
               Vui lòng hoàn thành thanh toán qua MoMo để chúng tôi bắt đầu chuẩn bị đơn hàng.</p>
        </div>
        @elseif(session('payment_success'))
        <div class="payment-info" style="background: #d4edda; border: 1px solid #c3e6cb;">
            <h4 style="color: #155724;"><i class="fas fa-check-circle"></i> Thanh Toán Thành Công</h4>
            <p style="color: #155724;">Cảm ơn bạn đã thanh toán qua MoMo. Đơn hàng của bạn đang được chuẩn bị.</p>
        </div>
        @endif
        
        <div class="actions">
            @if($donHang->trang_thai === 'cho_thanh_toan')
                <a href="{{ route('momo.payment', $donHang->ma_don_hang) }}" class="btn btn-primary">
                    <i class="fas fa-credit-card"></i> Thanh Toán Ngay
                </a>
            @endif
            
            <a href="{{ route('trangchu') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Về Trang Chủ
            </a>
            
            <a href="{{ route('dat-mon.index') }}" class="btn btn-outline">
                <i class="fas fa-shopping-cart"></i> Tiếp Tục Mua Sắm
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Auto refresh page every 30 seconds if order is being prepared
    @if($donHang->trang_thai === 'dang_chuan_bi' || $donHang->trang_thai === 'dang_giao')
    setTimeout(function() {
        location.reload();
    }, 30000);
    @endif
    
    // Show notification if payment is pending
    @if($donHang->trang_thai === 'cho_thanh_toan')
    setTimeout(function() {
        if (confirm('Bạn có muốn hoàn thành thanh toán ngay bây giờ?')) {
            window.location.href = '{{ route("momo.payment", $donHang->ma_don_hang) }}';
        }
    }, 3000);
    @endif
</script>
@endsection