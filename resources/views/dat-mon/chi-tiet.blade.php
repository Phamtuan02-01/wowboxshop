@extends('layouts.app')

@section('title', 'Chi tiết sản phẩm - ' . mb_convert_case($sanPham->ten_san_pham, MB_CASE_TITLE, 'UTF-8'))

@section('styles')
<style>
    :root {
        --primary-color: #004b00;
        --secondary-color: #006400;
        --accent-color: #00a000;
        --text-dark: #2c3e50;
        --text-light: #7f8c8d;
        --border-color: #e0e0e0;
        --shadow: 0 2px 10px rgba(0,0,0,0.1);
        --shadow-hover: 0 5px 20px rgba(0,0,0,0.15);
        --success-color: #004b00;
        --warning-color: #ffc107;
        --danger-color: #dc3545;
        --bg-color: #fff2ad;
    }

    .product-detail-section {
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        min-height: 100vh;
        padding-top: 100px;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 2rem;
    }

    .breadcrumb-item a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
    }

    .breadcrumb-item a:hover {
        color: var(--accent-color);
    }

    .breadcrumb-item.active {
        color: var(--text-dark);
        font-weight: 600;
    }

    .product-detail-container {
        background: transparent;
        border-radius: 30px;
        box-shadow: none;
        overflow: visible;
        margin-bottom: 3rem;
    }

    .product-image-section {
        background: transparent;
        padding: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image-container {
        position: relative;
        border-radius: 50%;
        overflow: hidden;
        box-shadow: 0 15px 40px rgba(0, 75, 0, 0.2);
        max-width: 550px;
        width: 100%;
        border: 5px solid white;
    }

    .product-main-image {
        width: 100%;
        height: 550px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-main-image:hover {
        transform: scale(1.1) rotate(5deg);
    }

    .product-info-section {
        padding: 3rem;
        background: white;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 75, 0, 0.15);
        border: 3px solid var(--primary-color);
    }

    .product-title {
        font-size: 3rem;
        font-weight: 300;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .product-category {
        margin-bottom: 1.5rem;
    }

    .category-badge {
        background: var(--primary-color);
        color: white;
        padding: 10px 25px;
        border-radius: 50px;
        font-size: 1rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        display: inline-block;
        border: 3px dashed white;
        box-shadow: 0 4px 15px rgba(0, 75, 0, 0.3);
    }

    .product-price {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #004b00 0%, #006400 100%);
        border-radius: 20px;
        text-align: center;
    }

    .price {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .product-description {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: #fff;
        border-radius: 15px;
        border: 2px solid var(--primary-color);
    }

    .product-description h5 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .product-description p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 0;
    }

    .variant-selection {
        margin-bottom: 2rem;
    }

    .variant-selection h5 {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .variant-option {
        border: 3px dashed var(--primary-color) !important;
        border-radius: 20px !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        height: auto;
        padding: 1rem;
        background: white !important;
    }

    .variant-option:hover {
        border-style: solid !important;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 75, 0, 0.2);
    }

    .btn-check:checked + .variant-option {
        background: var(--primary-color) !important;
        border-style: solid !important;
        border-color: var(--primary-color) !important;
        color: white !important;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 75, 0, 0.3);
    }

    .variant-info {
        text-align: center;
    }

    .variant-size {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: var(--primary-color);
    }

    .variant-price {
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 5px;
        color: var(--primary-color);
    }

    .variant-stock {
        font-size: 0.8rem;
    }

    /* Giữ màu khi hover */
    .variant-option:hover .variant-size,
    .variant-option:hover .variant-price {
        color: var(--primary-color);
    }

    /* Đổi màu trắng khi được chọn */
    .btn-check:checked + .variant-option .variant-size,
    .btn-check:checked + .variant-option .variant-price,
    .btn-check:checked + .variant-option .variant-price small {
        color: white !important;
    }

    /* Giá gạch ngang trong variant */
    .variant-price small {
        font-size: 0.75rem;
        color: var(--text-light);
    }

    .quantity-selection {
        margin-bottom: 2rem;
    }

    .quantity-selection h5 {
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 1rem;
    }

    .quantity-input {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: var(--shadow);
    }

    .quantity-input .btn {
        border: 3px solid var(--primary-color);
        background: var(--primary-color);
        color: white;
        font-weight: 700;
        padding: 12px 18px;
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .quantity-input .btn:hover {
        background: var(--accent-color);
        border-color: var(--accent-color);
        transform: scale(1.1);
    }

    .quantity-input .form-control {
        border: 3px solid var(--primary-color);
        font-weight: 700;
        font-size: 1.2rem;
        padding: 12px;
        color: var(--primary-color);
    }

    .quantity-input .form-control:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 75, 0, 0.25);
        border-color: var(--primary-color);
    }

    .stock-info {
        margin-top: 0.5rem;
        font-style: italic;
    }

    .total-price {
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        border-radius: 20px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
        border: 3px solid var(--primary-color);
        box-shadow: 0 5px 20px rgba(0, 75, 0, 0.2);
    }

    .total-price h5 {
        margin-bottom: 0;
        font-weight: 700;
        color: var(--primary-color);
        font-size: 1.3rem;
    }

    #totalPrice {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--primary-color);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .add-to-cart-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .add-to-cart-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 50px;
        padding: 18px 50px;
        font-weight: 700;
        font-size: 1.3rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        max-width: 100%;
        width: 100%;
        box-shadow: 0 10px 30px rgba(0, 75, 0, 0.3);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .add-to-cart-btn:hover:not(:disabled) {
        background: var(--accent-color);
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 160, 0, 0.4);
        color: white;
    }

    .add-to-cart-btn:disabled {
        background: #999;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
        opacity: 0.6;
    }

    .additional-info {
        background: white;
        border-radius: 30px;
        box-shadow: 0 10px 30px rgba(0, 75, 0, 0.15);
        overflow: hidden;
        border: 3px solid var(--primary-color);
    }

    .nav-tabs {
        border-bottom: 3px solid var(--primary-color);
        padding: 0 2rem;
        background: transparent;
    }

    .nav-tabs .nav-link {
        border: none;
        color: var(--text-light);
        font-weight: 700;
        padding: 1.5rem 2.5rem;
        border-radius: 0;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .nav-tabs .nav-link:hover {
        color: var(--primary-color);
        background: rgba(0, 75, 0, 0.05);
        border-color: transparent;
    }

    .nav-tabs .nav-link.active {
        color: white;
        background: var(--primary-color);
        border-color: transparent;
        border-radius: 15px 15px 0 0;
    }

    .tab-content {
        padding: 2.5rem;
        background: #fff;
    }

    .alert {
        border-radius: 12px;
        border: none;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
    }

    .alert-warning {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        color: #856404;
    }

    /* Flash Toast Notifications - Copy from header-footer.css */
    .toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
    }

    .flash-toast {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        margin-bottom: 15px;
        overflow: hidden;
        border: none;
        min-width: 320px;
        animation: slideInRight 0.4s ease-out;
        position: relative;
    }

    .flash-toast.fade-out {
        animation: slideOutRight 0.4s ease-in forwards;
    }

    .flash-toast .toast-header {
        display: flex;
        align-items: center;
        padding: 1rem 1.2rem 0.8rem;
        border-bottom: none;
        background: transparent;
    }

    .flash-toast .toast-body {
        padding: 0 1.2rem 1rem;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .flash-toast .toast-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 12px;
        flex-shrink: 0;
    }

    .flash-toast .toast-content {
        flex: 1;
    }

    .flash-toast .toast-title {
        font-weight: 600;
        margin: 0;
        font-size: 0.95rem;
    }

    .flash-toast .btn-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        opacity: 0.6;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: 10px;
        cursor: pointer;
    }

    .flash-toast .btn-close:hover {
        opacity: 1;
    }

    /* Success Toast */
    .flash-toast.toast-success {
        border-left: 4px solid #28a745;
    }

    .flash-toast.toast-success .toast-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .flash-toast.toast-success .toast-title {
        color: #155724;
    }

    .flash-toast.toast-success .toast-body {
        color: #155724;
    }

    /* Error Toast */
    .flash-toast.toast-error {
        border-left: 4px solid #dc3545;
    }

    .flash-toast.toast-error .toast-icon {
        background: linear-gradient(135deg, #dc3545, #e74c3c);
        color: white;
    }

    .flash-toast.toast-error .toast-title {
        color: #721c24;
    }

    .flash-toast.toast-error .toast-body {
        color: #721c24;
    }

    /* Warning Toast */
    .flash-toast.toast-warning {
        border-left: 4px solid #ffc107;
    }

    .flash-toast.toast-warning .toast-icon {
        background: linear-gradient(135deg, #ffc107, #f39c12);
        color: white;
    }

    .flash-toast.toast-warning .toast-title {
        color: #856404;
    }

    .flash-toast.toast-warning .toast-body {
        color: #856404;
    }

    /* Info Toast */
    .flash-toast.toast-info {
        border-left: 4px solid #17a2b8;
    }

    .flash-toast.toast-info .toast-icon {
        background: linear-gradient(135deg, #17a2b8, #3498db);
        color: white;
    }

    .flash-toast.toast-info .toast-title {
        color: #0c5460;
    }

    .flash-toast.toast-info .toast-body {
        color: #0c5460;
    }

    /* Progress bar */
    .flash-toast .progress-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(0, 0, 0, 0.2);
        animation: progressBar 3s linear forwards;
    }

    .flash-toast.toast-success .progress-bar {
        background: #28a745;
    }

    .flash-toast.toast-error .progress-bar {
        background: #dc3545;
    }

    .flash-toast.toast-warning .progress-bar {
        background: #ffc107;
    }

    .flash-toast.toast-info .progress-bar {
        background: #17a2b8;
    }

    /* Animations */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes progressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-detail-section {
            padding-top: 80px;
        }

        .product-title {
            font-size: 1.8rem;
        }

        .product-info-section {
            padding: 2rem;
        }

        .add-to-cart-actions {
            flex-direction: column;
        }

        .add-to-cart-btn {
            max-width: 100%;
        }

        .nav-tabs .nav-link {
            padding: 1rem;
        }
    }

    @media (max-width: 576px) {
        .product-image-section {
            padding: 1rem;
        }

        .product-info-section {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<section class="product-detail-section">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('trangchu') }}"><i class="fas fa-home me-2"></i>Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('dat-mon.index') }}"><i class="fas fa-utensils me-2"></i>Đặt món</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ mb_convert_case($sanPham->ten_san_pham, MB_CASE_TITLE, 'UTF-8') }}</li>
            </ol>
        </nav>

        <!-- Product Detail Container -->
        <div class="product-detail-container">
            <div class="row g-0">
                <!-- Product Image -->
                <div class="col-lg-6">
                    <div class="product-image-section">
                        <div class="product-image-container">
                            <img src="{{ $sanPham->hinh_anh ? asset('images/products/' . $sanPham->hinh_anh) : asset('images/default-product.jpg') }}" 
                                 alt="{{ $sanPham->ten_san_pham }}" 
                                 class="product-main-image">
                        </div>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-lg-6">
                    <div class="product-info-section">
                        <!-- Product Title -->
                        <h1 class="product-title">{{ mb_convert_case($sanPham->ten_san_pham, MB_CASE_TITLE, 'UTF-8') }}</h1>

                        <!-- Category -->
                        <div class="product-category">
                            <span class="category-badge">
                                <i class="fas fa-tag me-2"></i>
                                {{ $sanPham->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="product-price">
                            @if($sanPham->bienThes->count() > 0)
                                @if($sanPham->promotion_info['has_promotion'] ?? false)
                                    <!-- Có khuyến mãi -->
                                    <div style="margin-bottom: 10px;">
                                        <span class="price">{{ number_format($sanPham->promotion_info['discounted_min_price'], 0, ',', '.') }}đ</span>
                                        @if($sanPham->price_range['min'] != $sanPham->price_range['max'])
                                            <span class="price"> - {{ number_format($sanPham->promotion_info['discounted_max_price'], 0, ',', '.') }}đ</span>
                                        @endif
                                    </div>
                                    <div style="text-decoration: line-through; color: rgba(255,255,255,0.7); font-size: 1.2rem;">
                                        {{ number_format($sanPham->price_range['original_min'], 0, ',', '.') }}đ
                                        @if($sanPham->price_range['original_min'] != $sanPham->price_range['original_max'])
                                            - {{ number_format($sanPham->price_range['original_max'], 0, ',', '.') }}đ
                                        @endif
                                    </div>
                                    <div style="background: #e74c3c; color: white; padding: 5px 15px; border-radius: 20px; display: inline-block; margin-top: 10px; font-size: 1rem; font-weight: 700;">
                                        <i class="fas fa-tag me-1"></i>Giảm {{ $sanPham->promotion_info['discount_percentage'] ?? 0 }}%
                                    </div>
                                @else
                                    <!-- Không có khuyến mãi -->
                                    @php
                                        $minPrice = $sanPham->bienThes->min('gia');
                                        $maxPrice = $sanPham->bienThes->max('gia');
                                    @endphp
                                    @if($minPrice == $maxPrice)
                                        <span class="price">{{ number_format($minPrice) }}đ</span>
                                    @else
                                        <span class="price">{{ number_format($minPrice) }}đ - {{ number_format($maxPrice) }}đ</span>
                                    @endif
                                @endif
                            @else
                                <span class="price text-muted">Liên hệ</span>
                            @endif
                        </div>

                        <!-- Description -->
                        @if($sanPham->mo_ta)
                        <div class="product-description">
                            <h5><i class="fas fa-info-circle me-2"></i>Mô tả sản phẩm</h5>
                            <p>{{ $sanPham->mo_ta }}</p>
                        </div>
                        @endif

                        <!-- Add to Cart Form -->
                        @if($sanPham->bienThes->count() > 0)
                        <div class="add-to-cart-form">
                            <!-- Variant Selection -->
                            <div class="variant-selection">
                                <h5><i class="fas fa-resize me-2"></i>Chọn kích cỡ</h5>
                                <div class="row g-2">
                                    @foreach($sanPham->bienThes as $bienThe)
                                    @php
                                        // Tính giá sau khuyến mãi cho biến thể
                                        $originalPrice = $bienThe->gia;
                                        $discountedPrice = $originalPrice;
                                        
                                        if ($sanPham->promotion_info['has_promotion'] ?? false) {
                                            $discountPercentage = $sanPham->promotion_info['discount_percentage'] ?? 0;
                                            $discountedPrice = $originalPrice * (1 - $discountPercentage / 100);
                                        }
                                    @endphp
                                    <div class="col-6 col-md-4">
                                        <input type="radio" 
                                               class="btn-check size-select" 
                                               name="bien_the_id" 
                                               id="variant_{{ $bienThe->ma_bien_the }}" 
                                               value="{{ $bienThe->ma_bien_the }}"
                                               data-price="{{ $discountedPrice }}"
                                               data-original-price="{{ $originalPrice }}"
                                               data-has-promotion="{{ ($sanPham->promotion_info['has_promotion'] ?? false) ? 'true' : 'false' }}"
                                               data-stock="{{ $bienThe->so_luong_ton }}"
                                               data-product-id="{{ $sanPham->ma_san_pham }}"
                                               {{ $bienThe->so_luong_ton == 0 ? 'disabled' : '' }}>
                                        <label class="btn btn-outline-primary w-100 variant-option {{ $bienThe->so_luong_ton == 0 ? 'disabled' : '' }}" 
                                               for="variant_{{ $bienThe->ma_bien_the }}">
                                            <div class="variant-info">
                                                <div class="variant-size">{{ $bienThe->kich_co }}</div>
                                                <div class="variant-price">
                                                    @if($sanPham->promotion_info['has_promotion'] ?? false)
                                                        <span style="font-weight: 700;">{{ number_format($discountedPrice, 0, ',', '.') }}đ</span>
                                                        <br>
                                                        <small style="text-decoration: line-through; opacity: 0.7;">{{ number_format($originalPrice, 0, ',', '.') }}đ</small>
                                                    @else
                                                        {{ number_format($bienThe->gia, 0, ',', '.') }}đ
                                                    @endif
                                                </div>
                                                <div class="variant-stock">
                                                    @if($bienThe->so_luong_ton > 0)
                                                        <small class="text-success">
                                                            <i class="fas fa-check-circle me-1"></i>
                                                            Còn {{ $bienThe->so_luong_ton }}
                                                        </small>
                                                    @else
                                                        <small class="text-danger">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                            Hết hàng
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Quantity Selection -->
                            <div class="quantity-selection">
                                <h5><i class="fas fa-calculator me-2"></i>Số lượng</h5>
                                <div class="input-group quantity-input" style="max-width: 200px;">
                                    <button class="btn quantity-btn" type="button" data-action="minus">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="form-control text-center quantity-input-field" value="1" min="1" max="1">
                                    <button class="btn quantity-btn" type="button" data-action="plus">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <small class="text-muted stock-info">Vui lòng chọn kích cỡ</small>
                            </div>

                            <!-- Total Price -->
                            <div class="total-price">
                                <h5>
                                    <i class="fas fa-calculator me-2"></i>
                                    Tổng tiền: <span id="totalPrice" class="text-primary">0đ</span>
                                </h5>
                            </div>

                            <!-- Action Buttons -->
                            <div class="add-to-cart-actions">
                                <button type="button" class="add-to-cart-btn" 
                                        data-product-id="{{ $sanPham->ma_san_pham }}" 
                                        disabled>
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Thêm vào giỏ hàng
                                </button>
                            </div>
                        </div>
                        @else
                        <!-- Out of Stock Alert -->
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Thông báo:</strong> Sản phẩm này hiện tại chưa có sẵn. Vui lòng liên hệ để biết thêm thông tin.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Tabs -->
        <div class="additional-info">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Mô tả chi tiết
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="ingredients-tab" data-bs-toggle="tab" data-bs-target="#ingredients" type="button" role="tab">
                        <i class="fas fa-list-ul me-2"></i>Thành phần
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-0">
                        <p><i class="fas fa-quote-left me-2 text-primary"></i>{{ $sanPham->mo_ta ?: 'Chưa có mô tả chi tiết cho sản phẩm này.' }}</p>
                    </div>
                </div>
                <div class="tab-pane fade" id="ingredients" role="tabpanel">
                    <div class="p-0">
                        <p><i class="fas fa-leaf me-2 text-success"></i>{{ $sanPham->thanh_phan ?: 'Thông tin thành phần sẽ được cập nhật sớm.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>
@endsection

@section('scripts')
@section('scripts')
<script>
// Set up routes for JavaScript
window.routes = {
    addToCart: '{{ route("dat-mon.add-to-cart") }}',
    cart: '{{ route("giohang") }}',
    login: '{{ route("dangnhap") }}'
};

// Set user info for JavaScript
window.user = @auth true @else false @endauth;

$(document).ready(function() {
    let selectedVariant = null;
    let selectedPrice = 0;
    let maxStock = 0;

    // Update CSRF token for Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle variant selection
    $('.size-select').on('change', function() {
        const checkbox = $(this);
        selectedVariant = checkbox.val();
        selectedPrice = parseInt(checkbox.data('price'));
        maxStock = parseInt(checkbox.data('stock'));
        
        const productCard = checkbox.closest('.add-to-cart-form');
        const addToCartBtn = productCard.find('.add-to-cart-btn');
        const quantityInput = productCard.find('.quantity-input-field');
        const stockInfo = productCard.find('.stock-info');
        
        if (selectedVariant && maxStock > 0) {
            // Enable button and update text
            addToCartBtn.prop('disabled', false);
            addToCartBtn.html('<i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng');
            
            // Update max quantity
            quantityInput.attr('max', maxStock);
            if (parseInt(quantityInput.val()) > maxStock) {
                quantityInput.val(maxStock);
            }
            
            // Update stock info
            stockInfo.html(`<i class="fas fa-info-circle me-1"></i>Còn lại ${maxStock} sản phẩm`);
            stockInfo.removeClass('text-muted').addClass('text-success');
            
            // Update total price
            updateTotalPrice();
            
        } else {
            // Disable button
            addToCartBtn.prop('disabled', true);
            addToCartBtn.html('<i class="fas fa-shopping-cart me-2"></i>Chọn kích cỡ');
            
            stockInfo.html('<i class="fas fa-exclamation-circle me-1"></i>Vui lòng chọn kích cỡ');
            stockInfo.removeClass('text-success').addClass('text-muted');
            
            $('#totalPrice').text('0đ');
        }
    });

    // Handle quantity change buttons
    $('.quantity-btn').on('click', function() {
        const action = $(this).data('action');
        const quantityInput = $('.quantity-input-field');
        let currentValue = parseInt(quantityInput.val()) || 1;
        const maxValue = parseInt(quantityInput.attr('max')) || 999;
        
        if (action === 'plus' && currentValue < maxValue) {
            quantityInput.val(currentValue + 1);
        } else if (action === 'minus' && currentValue > 1) {
            quantityInput.val(currentValue - 1);
        }
        
        updateTotalPrice();
    });

    // Validate quantity on direct input
    $('.quantity-input-field').on('input', function() {
        let value = parseInt($(this).val()) || 1;
        const max = parseInt($(this).attr('max')) || 999;
        const min = parseInt($(this).attr('min')) || 1;
        
        if (value < min) value = min;
        if (value > max) value = max;
        
        $(this).val(value);
        updateTotalPrice();
    });

    // Update total price function
    function updateTotalPrice() {
        if (selectedPrice > 0) {
            const quantity = parseInt($('.quantity-input-field').val()) || 1;
            const total = selectedPrice * quantity;
            $('#totalPrice').text(new Intl.NumberFormat('vi-VN').format(total) + 'đ');
        }
    }

    // Handle add to cart
    $('.add-to-cart-btn').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const quantity = parseInt($('.quantity-input-field').val()) || 1;
        
        if (!selectedVariant) {
            showError('Vui lòng chọn kích cỡ');
            return;
        }
        
        if (!window.user) {
            showError('Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng');
            setTimeout(() => {
                window.location.href = window.routes.login;
            }, 2000);
            return;
        }
        
        // Disable button and add loading
        button.prop('disabled', true);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin me-2"></i>Đang thêm...');
        
        $.ajax({
            url: window.routes.addToCart,
            method: 'POST',
            data: {
                ma_san_pham: productId,
                ma_bien_the: selectedVariant,
                so_luong: quantity,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showSuccess(response.message);
                    
                    // Animation for button
                    button.removeClass('add-to-cart-btn').addClass('btn').addClass('btn-success');
                    button.html('<i class="fas fa-check me-2"></i>Đã thêm vào giỏ!');
                    
                    // Reset button after 2 seconds for continue shopping
                    setTimeout(() => {
                        button.removeClass('btn-success').addClass('add-to-cart-btn');
                        button.prop('disabled', false);
                        button.html(originalText);
                    }, 2000);
                    
                } else {
                    showError(response.message);
                    button.prop('disabled', false);
                    button.html(originalText);
                }
            },
            error: function(xhr) {
                let message = 'Có lỗi xảy ra, vui lòng thử lại';
                
                if (xhr.status === 401) {
                    message = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng';
                    setTimeout(() => {
                        window.location.href = window.routes.login;
                    }, 2000);
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                
                showError(message);
                button.prop('disabled', false);
                button.html(originalText);
            }
        });
    });

    // Local showToast function for this page
    function showToast(type, title, message) {
        const container = document.getElementById('toastContainer');
        if (!container) return;

        const icons = {
            'success': 'fas fa-check',
            'error': 'fas fa-times',
            'warning': 'fas fa-exclamation',
            'info': 'fas fa-info'
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
                    <div class="toast-title">${title}</div>
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
        
        // Auto remove after 3 seconds
        setTimeout(function() {
            closeToast(toastDiv.querySelector('.btn-close'));
        }, 3000);
    }

    // Close toast function
    function closeToast(button) {
        const toast = button.closest('.flash-toast');
        if (toast) {
            toast.classList.add('fade-out');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 400);
        }
    }

    // Show success message
    function showSuccess(message) {
        showToast('success', 'Thành công!', message);
    }

    // Show error message
    function showError(message) {
        showToast('error', 'Lỗi!', message);
    }

    // Smooth scroll for anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });

    // Initialize tooltips if Bootstrap is available
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>
@endsection
@endsection