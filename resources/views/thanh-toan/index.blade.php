@extends('layouts.app')

@section('title', 'Thanh Toán - WOW Box Shop')

@section('styles')
<style>
    body {
        background: linear-gradient(to bottom, #FFE135, #FFF7A0);
        min-height: 100vh;
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }

    .checkout-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .checkout-header h1 {
        color: #004b00;
        font-size: 2.5rem;
        font-weight: 300;
        margin-bottom: 10px;
    }

    .checkout-header p {
        color: #666;
        font-size: 1.1rem;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 40px;
    }

    .checkout-form {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .order-summary {
        background: white;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .section-title {
        color: #004b00;
        font-size: 1.5rem;
        font-weight: 400;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #004b00;
    }

    .delivery-method {
        margin-bottom: 30px;
    }

    .delivery-options {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .delivery-option {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }

    .delivery-option:hover {
        border-color: #004b00;
        background: #f8fff8;
    }

    .delivery-option.active {
        border-color: #004b00;
        background: #f8fff8;
        box-shadow: 0 4px 15px rgba(0, 75, 0, 0.2);
    }

    .delivery-option input[type="radio"] {
        display: none;
    }

    .delivery-option .icon {
        font-size: 2rem;
        color: #004b00;
        margin-bottom: 10px;
    }

    .delivery-option .title {
        font-size: 1.1rem;
        font-weight: 500;
        color: #004b00;
        margin-bottom: 5px;
    }

    .delivery-option .desc {
        font-size: 0.9rem;
        color: #666;
    }

    .form-section {
        margin-bottom: 30px;
        display: none;
    }

    .form-section.active {
        display: block;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        color: #004b00;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #004b00;
        box-shadow: 0 0 0 3px rgba(0, 75, 0, 0.1);
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .store-finder {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 20px;
        margin-top: 20px;
    }

    .store-finder h4 {
        color: #004b00;
        margin-bottom: 15px;
    }

    .store-result {
        display: none;
        margin-top: 20px;
    }

    .store-card {
        background: white;
        border-radius: 10px;
        padding: 15px;
        border: 2px solid #e0e0e0;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .store-card:hover {
        border-color: #004b00;
    }

    .store-card.selected {
        border-color: #004b00;
        background: #f8fff8;
    }

    .store-name {
        font-weight: 500;
        color: #004b00;
        margin-bottom: 5px;
    }

    .store-address {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .store-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .store-distance {
        background: #004b00;
        color: white;
        padding: 3px 8px;
        border-radius: 15px;
        font-size: 0.8rem;
    }

    .payment-method {
        margin-bottom: 30px;
    }

    .payment-options {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .payment-option {
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .payment-option:hover {
        border-color: #004b00;
        background: #f8fff8;
    }

    .payment-option.active {
        border-color: #004b00;
        background: #f8fff8;
    }

    .payment-option input[type="radio"] {
        display: none;
    }

    .payment-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .momo-icon {
        background: linear-gradient(135deg, #a50064, #d82d8b);
    }

    .order-items {
        margin-bottom: 25px;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-item:last-child {
        border-bottom: none;
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

    .item-variant {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .item-quantity {
        font-size: 0.9rem;
        color: #666;
    }

    .item-price {
        font-weight: 500;
        color: #004b00;
    }

    .order-totals {
        border-top: 2px solid #f0f0f0;
        padding-top: 20px;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .total-row.final {
        border-top: 2px solid #004b00;
        padding-top: 15px;
        margin-top: 15px;
        font-size: 1.2rem;
        font-weight: 600;
        color: #004b00;
    }

    .checkout-btn {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #004b00, #006600);
        color: white;
        border: none;
        border-radius: 15px;
        font-size: 1.2rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 20px;
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 75, 0, 0.3);
    }

    .checkout-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .alert {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
    }

    .alert-error {
        background: #ffe6e6;
        color: #d63031;
        border: 1px solid #fab1a0;
    }

    .alert-success {
        background: #e6ffe6;
        color: #00b894;
        border: 1px solid #81ecec;
    }

    .loading {
        display: none;
        text-align: center;
        padding: 20px;
    }

    .spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #004b00;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        animation: spin 1s linear infinite;
        margin: 0 auto 10px;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Responsive */
    @media (max-width: 992px) {
        .checkout-content {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .order-summary {
            position: static;
            order: -1;
        }

        .delivery-options {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .checkout-container {
            padding: 20px 15px;
        }

        .checkout-header h1 {
            font-size: 2rem;
        }

        .checkout-form,
        .order-summary {
            padding: 20px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="checkout-container">
    <div class="checkout-header">
        <h1>Thanh Toán Đơn Hàng</h1>
        <p>Vui lòng kiểm tra thông tin và hoàn tất đơn hàng của bạn</p>
    </div>

    <div class="checkout-content">
        <div class="checkout-form">
            <form id="checkoutForm">
                @csrf
                
                <!-- Phương thức giao hàng -->
                <div class="delivery-method">
                    <h3 class="section-title">Phương Thức Giao Hàng</h3>
                    
                    <div class="delivery-options">
                        <div class="delivery-option active" data-method="giao_hang">
                            <input type="radio" name="phuong_thuc_giao_hang" value="giao_hang" checked>
                            <div class="icon"><i class="fas fa-truck"></i></div>
                            <div class="title">Giao Hàng Tận Nơi</div>
                            <div class="desc">Phí ship: 10,000₫</div>
                        </div>
                        
                        <div class="delivery-option" data-method="nhan_tai_cua_hang">
                            <input type="radio" name="phuong_thuc_giao_hang" value="nhan_tai_cua_hang">
                            <div class="icon"><i class="fas fa-store"></i></div>
                            <div class="title">Nhận Tại Cửa Hàng</div>
                            <div class="desc">Miễn phí</div>
                        </div>
                    </div>

                    <!-- Form giao hàng tận nơi -->
                    <div class="form-section active" id="giao_hang_form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="ho_ten">Họ và Tên *</label>
                                <input type="text" class="form-control" id="ho_ten" name="ho_ten" required>
                            </div>
                            <div class="form-group">
                                <label for="so_dien_thoai">Số Điện Thoại *</label>
                                <input type="tel" class="form-control" id="so_dien_thoai" name="so_dien_thoai" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="dia_chi">Địa Chỉ Chi Tiết *</label>
                            <input type="text" class="form-control" id="dia_chi" name="dia_chi" 
                                   placeholder="Số nhà, tên đường, phường/xã" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tinh_thanh_pho">Tỉnh/Thành Phố *</label>
                            <select class="form-control" id="tinh_thanh_pho" name="tinh_thanh_pho" required>
                                <option value="">Chọn Tỉnh/Thành Phố</option>
                                <option value="TP. Hồ Chí Minh">TP. Hồ Chí Minh</option>
                                <option value="Hà Nội">Hà Nội</option>
                                <option value="Đà Nẵng">Đà Nẵng</option>
                                <option value="Cần Thơ">Cần Thơ</option>
                                <option value="Bình Dương">Bình Dương</option>
                                <option value="Đồng Nai">Đồng Nai</option>
                                <option value="Long An">Long An</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form nhận tại cửa hàng -->
                    <div class="form-section" id="nhan_tai_cua_hang_form">
                        <div class="store-finder">
                            <h4>Tìm Cửa Hàng Gần Nhất</h4>
                            <div class="form-group">
                                <label for="dia_chi_tim_kiem">Nhập địa chỉ của bạn</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" class="form-control" id="dia_chi_tim_kiem" 
                                           placeholder="VD: Quận 1, TP.HCM">
                                    <button type="button" class="btn btn-outline-primary" onclick="timCuaHang()">
                                        Tìm Kiếm
                                    </button>
                                </div>
                            </div>
                            
                            <div class="loading" id="store-loading">
                                <div class="spinner"></div>
                                <div>Đang tìm cửa hàng gần nhất...</div>
                            </div>
                            
                            <div class="store-result" id="store-result">
                                <!-- Kết quả tìm kiếm sẽ hiển thị ở đây -->
                            </div>
                        </div>
                        
                        <input type="hidden" name="cua_hang_chon" id="cua_hang_chon">
                    </div>
                </div>

                <!-- Phương thức thanh toán -->
                <div class="payment-method">
                    <h3 class="section-title">Phương Thức Thanh Toán</h3>
                    
                    <div class="payment-options">
                        <div class="payment-option" data-method="cod">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="cod">
                            <div class="payment-icon" style="background: linear-gradient(135deg, #28a745, #20c997);">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div>
                                <div class="title">Thanh Toán COD</div>
                                <div class="desc">Thanh toán khi nhận hàng</div>
                            </div>
                        </div>
                        
                        <div class="payment-option" data-method="demo">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="demo">
                            <div class="payment-icon" style="background: linear-gradient(135deg, #007bff, #0056b3);">
                                <i class="fas fa-code"></i>
                            </div>
                            <div>
                                <div class="title">Thanh Toán Demo</div>
                                <div class="desc">Dành cho demo hệ thống</div>
                            </div>
                        </div>
                        
                        <div class="payment-option" data-method="momo">
                            <input type="radio" name="phuong_thuc_thanh_toan" value="momo">
                            <div class="payment-icon momo-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div>
                                <div class="title">Thanh Toán MoMo</div>
                                <div class="desc">Thanh toán qua ví điện tử MoMo (Thật)</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ghi chú -->
                <div class="form-group">
                    <label for="ghi_chu">Ghi Chú Đơn Hàng</label>
                    <textarea class="form-control" id="ghi_chu" name="ghi_chu" rows="3" 
                              placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)"></textarea>
                </div>

                <button type="submit" class="checkout-btn" id="submitBtn">
                    Hoàn Tất Đơn Hàng
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <h3 class="section-title">Đơn Hàng Của Bạn</h3>
            
            <div class="order-items">
                @foreach($gioHang->chiTietGioHangs as $chiTiet)
                @php
                    $giaGoc = $chiTiet->bienThe->gia;
                    $giaKhuyenMai = $chiTiet->sanPham ? $chiTiet->sanPham->getDiscountedPriceForVariant($giaGoc) : $giaGoc;
                    $coKhuyenMai = $giaGoc > $giaKhuyenMai;
                @endphp
                <div class="order-item">
                    <img src="{{ $chiTiet->sanPham && $chiTiet->sanPham->hinh_anh ? asset('images/products/' . $chiTiet->sanPham->hinh_anh) : asset('images/default-product.png') }}" 
                         alt="{{ $chiTiet->sanPham ? $chiTiet->sanPham->ten_san_pham : 'Sản phẩm' }}" class="item-image"
                         onerror="this.src='{{ asset('images/default-product.png') }}'">
                    <div class="item-info">
                        <div class="item-name">{{ $chiTiet->sanPham->ten_san_pham }}</div>
                        <div class="item-variant">Kích thước: {{ $chiTiet->bienThe->kich_thuoc ?? 'Mặc định' }}</div>
                        <div class="item-quantity">Số lượng: {{ $chiTiet->so_luong }}</div>
                        @if($coKhuyenMai)
                            <small style="color: #28a745;">
                                <i class="fas fa-tag"></i> Tiết kiệm {{ number_format(($giaGoc - $giaKhuyenMai) * $chiTiet->so_luong) }}₫
                            </small>
                        @endif
                    </div>
                    <div class="item-price">
                        @if($coKhuyenMai)
                            <div style="text-decoration: line-through; color: #999; font-size: 0.9rem;">
                                {{ number_format($giaGoc * $chiTiet->so_luong) }}₫
                            </div>
                            <div style="color: #dc3545; font-weight: bold;">
                                {{ number_format($giaKhuyenMai * $chiTiet->so_luong) }}₫
                            </div>
                        @else
                            {{ number_format($giaGoc * $chiTiet->so_luong) }}₫
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="order-totals">
                @if(isset($tongTietKiem) && $tongTietKiem > 0)
                <div class="total-row">
                    <span>Tổng tiền gốc:</span>
                    <span style="text-decoration: line-through; color: #999;">{{ number_format($tongTienGoc) }}₫</span>
                </div>
                <div class="total-row" style="color: #28a745;">
                    <span><i class="fas fa-tag"></i> Tiết kiệm:</span>
                    <span>-{{ number_format($tongTietKiem) }}₫</span>
                </div>
                @endif
                <div class="total-row">
                    <span>Tạm tính:</span>
                    <span id="subtotal-amount">{{ number_format($tongTien) }}₫</span>
                </div>
                
                <!-- Form nhập mã khuyến mãi -->
                <div class="promotion-code-section" style="margin: 15px 0; padding: 15px; border: 2px dashed #004b00; border-radius: 10px; background: #f8f9fa;">
                    <div style="display: flex; gap: 10px; margin-bottom: 10px;">
                        <input type="text" id="promotion-code-input" placeholder="Nhập mã khuyến mãi" 
                               style="flex: 1; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 14px;">
                        <button type="button" id="apply-promotion-btn" 
                                style="padding: 10px 20px; background: #004b00; color: white; border: none; border-radius: 5px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                            <i class="fas fa-check"></i> Áp dụng
                        </button>
                    </div>
                    <div id="promotion-message" style="font-size: 13px; margin-top: 5px;"></div>
                    <div id="applied-promotion" style="display: none; margin-top: 10px; padding: 10px; background: #d4edda; border-radius: 5px; color: #155724;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="promotion-name" style="font-weight: 600;"></span>
                            <button type="button" id="remove-promotion-btn" style="background: none; border: none; color: #721c24; cursor: pointer; font-size: 18px;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <small id="promotion-desc"></small>
                    </div>
                </div>
                
                <div class="total-row" id="promotion-discount-row" style="display: none; color: #28a745;">
                    <span><i class="fas fa-ticket-alt"></i> Giảm giá từ mã:</span>
                    <span id="promotion-discount">-0₫</span>
                </div>
                
                <div class="total-row" id="shipping-fee-row">
                    <span>Phí giao hàng:</span>
                    <span id="shipping-fee">{{ number_format($phiGiaoHang) }}₫</span>
                </div>
                <div class="total-row final">
                    <span>Tổng cộng:</span>
                    <span id="total-amount">{{ number_format($tongTien + $phiGiaoHang) }}₫</span>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.custom-alert')
@endsection

@section('scripts')
<script>
    const tongTienGoc = {{ $tongTien }};
    const phiGiaoHangGoc = {{ $phiGiaoHang }};
    
    // Delivery method toggle
    document.querySelectorAll('.delivery-option').forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            document.querySelectorAll('.delivery-option').forEach(opt => opt.classList.remove('active'));
            document.querySelectorAll('.form-section').forEach(section => section.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            const method = this.dataset.method;
            this.querySelector('input[type="radio"]').checked = true;
            
            // Show corresponding form
            document.getElementById(method + '_form').classList.add('active');
            
            // Update shipping fee
            const phiGiaoHang = method === 'giao_hang' ? phiGiaoHangGoc : 0;
            document.getElementById('shipping-fee').textContent = Math.round(phiGiaoHang).toLocaleString('de-DE') + '₫';
            document.getElementById('total-amount').textContent = Math.round(tongTienGoc - promotionDiscount + phiGiaoHang).toLocaleString('de-DE') + '₫';
            
            // Show/hide shipping fee row
            const shippingRow = document.getElementById('shipping-fee-row');
            if (method === 'nhan_tai_cua_hang') {
                shippingRow.style.display = 'none';
            } else {
                shippingRow.style.display = 'flex';
            }
        });
    });

    // Payment method toggle
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // Store selection
    function selectStore(element, tenCuaHang, diaChiCuaHang) {
        document.querySelectorAll('.store-card').forEach(card => card.classList.remove('selected'));
        element.classList.add('selected');
        document.getElementById('cua_hang_chon').value = tenCuaHang + ' - ' + diaChiCuaHang;
    }

    // Find nearest store
    function timCuaHang() {
        const diaChiInput = document.getElementById('dia_chi_tim_kiem');
        const diaChi = diaChiInput.value.trim();
        
        if (!diaChi) {
            showCustomAlert('Vui lòng nhập địa chỉ', 'warning');
            return;
        }
        
        const loading = document.getElementById('store-loading');
        const result = document.getElementById('store-result');
        
        loading.style.display = 'block';
        result.style.display = 'none';
        
        fetch('{{ route("thanh-toan.tim-cua-hang") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ dia_chi: diaChi })
        })
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            result.style.display = 'block';
            
            if (data.success) {
                let html = '<h5 style="color: #004b00; margin-bottom: 15px;">Cửa hàng có thể phục vụ bạn:</h5>';
                
                data.danh_sach_cua_hangs.forEach(cuaHang => {
                    html += `
                        <div class="store-card" onclick="selectStore(this, '${cuaHang.ten}', '${cuaHang.dia_chi}')">
                            <div class="store-name">${cuaHang.ten}</div>
                            <div class="store-address">${cuaHang.dia_chi}</div>
                            <div class="store-info">
                                <div>
                                    <i class="fas fa-clock"></i> ${cuaHang.gio_mo} - ${cuaHang.gio_dong} |
                                    <i class="fas fa-phone"></i> ${cuaHang.sdt}
                                </div>
                                <div class="store-distance">${cuaHang.khoang_cach}km</div>
                            </div>
                        </div>
                    `;
                });
                
                result.innerHTML = html;
            } else {
                result.innerHTML = `
                    <div style="text-align: center; color: #d63031; padding: 20px;">
                        <i class="fas fa-exclamation-triangle" style="font-size: 2rem; margin-bottom: 10px;"></i>
                        <div style="font-weight: 500; margin-bottom: 10px;">${data.message}</div>
                        ${data.cua_hang_gan_nhat ? `
                            <div style="margin-top: 15px; padding: 15px; background: #fff3cd; border-radius: 10px;">
                                <strong>Cửa hàng gần nhất:</strong><br>
                                ${data.cua_hang_gan_nhat.ten}<br>
                                <small>${data.cua_hang_gan_nhat.dia_chi} (${data.cua_hang_gan_nhat.khoang_cach}km)</small>
                            </div>
                        ` : ''}
                    </div>
                `;
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            result.style.display = 'block';
            result.innerHTML = '<div style="color: #d63031; text-align: center;">Có lỗi xảy ra khi tìm cửa hàng</div>';
        });
    }

    // Promotion code handling
    let appliedPromotion = null;
    let promotionDiscount = 0;

    function updateTotal() {
        const phuongThucGiaoHang = document.querySelector('input[name="phuong_thuc_giao_hang"]:checked');
        const phiGiaoHang = phuongThucGiaoHang && phuongThucGiaoHang.value === 'giao_hang' ? phiGiaoHangGoc : 0;
        const tongCong = tongTienGoc - promotionDiscount + phiGiaoHang;
        
        document.getElementById('total-amount').textContent = Math.round(tongCong).toLocaleString('de-DE') + '₫';
        
        // Update shipping fee
        document.getElementById('shipping-fee').textContent = Math.round(phiGiaoHang).toLocaleString('de-DE') + '₫';
    }

    document.getElementById('apply-promotion-btn').addEventListener('click', function() {
        const code = document.getElementById('promotion-code-input').value.trim();
        const btn = this;
        const messageDiv = document.getElementById('promotion-message');
        
        if (!code) {
            messageDiv.innerHTML = '<span style="color: #dc3545;"><i class="fas fa-exclamation-circle"></i> Vui lòng nhập mã khuyến mãi</span>';
            return;
        }
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang kiểm tra...';
        messageDiv.innerHTML = '';
        
        fetch('{{ route("promotions.check-code") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                code: code,
                order_value: tongTienGoc
            })
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Áp dụng';
            
            if (data.valid) {
                const promotion = data.promotion;
                
                // Tính giảm giá (chỉ hỗ trợ loại fixed)
                if (promotion.type === 'fixed') {
                    promotionDiscount = promotion.value;
                    if (promotionDiscount > tongTienGoc) {
                        promotionDiscount = tongTienGoc;
                    }
                }
                
                appliedPromotion = promotion;
                
                // Hiển thị thông tin khuyến mãi
                document.getElementById('promotion-name').textContent = promotion.name;
                document.getElementById('promotion-desc').textContent = promotion.display || `Giảm ${Math.round(promotionDiscount).toLocaleString('de-DE')}₫`;
                document.getElementById('applied-promotion').style.display = 'block';
                document.getElementById('promotion-code-input').disabled = true;
                btn.style.display = 'none';
                
                // Hiển thị dòng giảm giá
                document.getElementById('promotion-discount').textContent = '-' + Math.round(promotionDiscount).toLocaleString('de-DE') + '₫';
                document.getElementById('promotion-discount-row').style.display = 'flex';
                
                updateTotal();
                
                messageDiv.innerHTML = `<span style="color: #28a745;"><i class="fas fa-check-circle"></i> Áp dụng mã thành công!</span>`;
            } else {
                messageDiv.innerHTML = `<span style="color: #dc3545;"><i class="fas fa-exclamation-circle"></i> ${data.message}</span>`;
            }
        })
        .catch(error => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check"></i> Áp dụng';
            messageDiv.innerHTML = '<span style="color: #dc3545;"><i class="fas fa-exclamation-circle"></i> Có lỗi xảy ra, vui lòng thử lại</span>';
        });
    });

    document.getElementById('remove-promotion-btn').addEventListener('click', function() {
        appliedPromotion = null;
        promotionDiscount = 0;
        
        document.getElementById('applied-promotion').style.display = 'none';
        document.getElementById('promotion-code-input').value = '';
        document.getElementById('promotion-code-input').disabled = false;
        document.getElementById('apply-promotion-btn').style.display = 'block';
        document.getElementById('promotion-discount-row').style.display = 'none';
        document.getElementById('promotion-message').innerHTML = '';
        
        updateTotal();
    });

    // Form submission
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = document.getElementById('submitBtn');
        
        // Validation
        const phuongThucGiaoHang = formData.get('phuong_thuc_giao_hang');
        
        if (phuongThucGiaoHang === 'giao_hang') {
            if (!formData.get('ho_ten') || !formData.get('so_dien_thoai') || 
                !formData.get('dia_chi') || !formData.get('tinh_thanh_pho')) {
                showCustomAlert('Vui lòng điền đầy đủ thông tin giao hàng', 'warning');
                return;
            }
        } else {
            if (!formData.get('cua_hang_chon')) {
                showCustomAlert('Vui lòng chọn cửa hàng để nhận hàng', 'warning');
                return;
            }
        }
        
        // Thêm thông tin khuyến mãi vào formData
        if (appliedPromotion) {
            formData.append('ma_khuyen_mai', appliedPromotion.id);
            formData.append('giam_gia_khuyen_mai', promotionDiscount);
        }
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        
        fetch('{{ route("thanh-toan.dat-hang") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.payment_url) {
                    // Redirect to MoMo payment
                    window.location.href = data.payment_url;
                } else if (data.demo_url) {
                    // Redirect to demo payment
                    window.location.href = data.demo_url;
                } else if (data.redirect_url) {
                    // Redirect to success page (COD)
                    window.location.href = data.redirect_url;
                }
            } else {
                showCustomAlert(data.message, 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Hoàn Tất Đơn Hàng';
            }
        })
        .catch(error => {
            showCustomAlert('Có lỗi xảy ra, vui lòng thử lại', 'error');
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Hoàn Tất Đơn Hàng';
        });
    });
</script>
@endsection