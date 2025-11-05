@extends('layouts.app')

@section('title', 'Tự Chọn - WOW Box Shop')

@section('styles')
<style>
    body {
        background: linear-gradient(to bottom, #FFE135, #FFF7A0);
        min-height: 100vh;
    }

    /* Banner Section */
    .banner-section {
        width: 100%;
        height: 80vh;
        margin-bottom: 20px;
        position: relative;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .banner-section img {
        width: 100%;
        height: auto;
        display: block;
    }

    .banner-content {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        width: 90%;
    }

    .banner-content h1 {
        font-size: 3.5rem;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .banner-content p {
        font-size: 1.5rem;
        font-weight: 300;
        margin: 0;
    }

    /* Category Tabs */
    .category-tabs {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .category-tab {
        padding: 12px 30px;
        background: white;
        border: 2px solid #004b00;
        border-radius: 25px;
        color: #004b00;
        font-size: 1.1rem;
        font-weight: 300;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-tab:hover,
    .category-tab.active {
        background: #004b00;
        color: white;
    }

    /* Product Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 20px;
        margin-bottom: 40px;
    }

    .category-group {
        grid-column: 1 / -1;
    }

    .category-group h3 {
        color: #004b00;
        font-size: 1.8rem;
        font-weight: 300;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #004b00;
    }

    .category-products {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 20px;
    }

    /* Product Card */
    .ingredient-card {
        background: transparent;
        border-radius: 15px;
        overflow: visible;
        box-shadow: none;
        display: flex;
        flex-direction: column;
        position: relative;
        height: 420px;
    }

    .ingredient-card-title {
        padding: 15px;
        text-align: center;
        height: 90px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .ingredient-card-title h4 {
        font-size: 1.3rem;
        font-weight: 400;
        color: #004b00;
        margin: 0;
        line-height: 1.4;
    }

    .ingredient-card-image-wrapper {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 2;
        padding: 0 20px;
    }

    .ingredient-card-image {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #004b00;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        background: white;
        transform: translateY(30px);
    }

    .ingredient-card-box {
        background: linear-gradient(135deg, #004b00, #006600);
        border-radius: 0 0 15px 15px;
        padding: 40px 15px 15px 15px;
        height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        position: relative;
        z-index: 1;
    }

    .ingredient-price {
        color: white;
        font-size: 1.3rem;
        font-weight: 300;
        text-align: center;
        margin-bottom: 15px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .quantity-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: white;
        color: #004b00;
        border: none;
        font-size: 1.5rem;
        font-weight: 300;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .quantity-btn:hover {
        background: #FFE135;
        transform: scale(1.1);
    }

    .quantity-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .quantity-display {
        width: 50px;
        height: 35px;
        background: white;
        color: #004b00;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        font-weight: 300;
    }

    /* Floating Cart */
    .floating-cart {
        position: fixed;
        right: 20px;
        top: 100px;
        width: 350px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        padding: 20px;
        max-height: calc(100vh - 140px);
        overflow-y: auto;
        z-index: 1000;
        transition: transform 0.3s ease, opacity 0.3s ease;
    }

    .floating-cart.hidden {
        transform: translateX(400px);
        opacity: 0;
        pointer-events: none;
    }

    /* Cart Toggle Button */
    .cart-toggle-btn {
        position: fixed;
        right: 20px;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background: #004b00;
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        z-index: 1001;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: right 0.3s ease, transform 0.3s ease, background 0.3s ease;
    }

    .cart-toggle-btn:hover {
        background: #006600;
        transform: translateY(-50%) scale(1.1);
    }

    .cart-toggle-btn.active {
        right: 390px;
    }

    .cart-toggle-count {
        position: absolute;
        top: -5px;
        right: -5px;
        width: 25px;
        height: 25px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .floating-cart h3 {
        color: #004b00;
        font-size: 1.5rem;
        font-weight: 300;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #004b00;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
        margin-bottom: 10px;
    }

    .cart-item-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #004b00;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-size: 0.9rem;
        color: #004b00;
        font-weight: 300;
        margin-bottom: 5px;
    }

    .cart-item-details {
        font-size: 0.8rem;
        color: #666;
    }

    .cart-item-remove {
        width: 25px;
        height: 25px;
        border-radius: 50%;
        background: #dc3545;
        color: white;
        border: none;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .cart-item-remove:hover {
        background: #c82333;
        transform: scale(1.1);
    }

    .cart-total {
        margin: 20px 0;
        padding: 15px;
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        border-radius: 10px;
        text-align: center;
    }

    .cart-total-label {
        font-size: 1rem;
        color: #004b00;
        font-weight: 300;
        margin-bottom: 5px;
    }

    .cart-total-amount {
        font-size: 1.8rem;
        color: #004b00;
        font-weight: 400;
    }

    .cart-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .cart-btn {
        padding: 12px;
        border-radius: 10px;
        border: none;
        font-size: 1.1rem;
        font-weight: 300;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .cart-btn-primary {
        background: #004b00;
        color: white;
    }

    .cart-btn-primary:hover {
        background: #006600;
        transform: translateY(-2px);
    }

    .cart-btn-secondary {
        background: white;
        color: #dc3545;
        border: 2px solid #dc3545;
    }

    .cart-btn-secondary:hover {
        background: #dc3545;
        color: white;
    }

    .empty-cart-message {
        text-align: center;
        color: #666;
        font-size: 1rem;
        padding: 30px 0;
    }

    /* Responsive */
    @media (max-width: 1400px) {
        .products-grid,
        .category-products {
            grid-template-columns: repeat(4, 1fr);
        }
        
        .floating-cart {
            width: 300px;
        }
    }

    @media (max-width: 992px) {
        .products-grid,
        .category-products {
            grid-template-columns: repeat(3, 1fr);
        }
        
        .floating-cart {
            position: static;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .floating-cart.hidden {
            transform: none;
            opacity: 1;
            pointer-events: auto;
        }
        
        .cart-toggle-btn {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .products-grid,
        .category-products {
            grid-template-columns: repeat(2, 1fr);
        }

        .banner-content h1 {
            font-size: 2rem;
        }

        .banner-content p {
            font-size: 1rem;
        }

        .ingredient-card {
            height: 370px;
        }

        .ingredient-card-title h4 {
            font-size: 1.1rem;
        }

        .ingredient-card-image {
            width: 100px;
            height: 100px;
        }

        .ingredient-card-box {
            height: 175px;
        }
    }

    @media (max-width: 576px) {
        .products-grid,
        .category-products {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 py-3">
    <!-- Banner Section -->
    <div class="banner-section">
        <img src="{{ asset('images/veChungToi/TamNhinSuMenh.jpg') }}" alt="Tự Chọn Banner">
        <div class="banner-content">
            <h1>TỰ CHỌN NGUYÊN LIỆU</h1>
            <p>Tự do sáng tạo món ăn theo cách riêng của bạn</p>
        </div>
    </div>

    <!-- Cart Toggle Button -->
    <button class="cart-toggle-btn active" id="cartToggleBtn" onclick="toggleCart()">
        <i class="fas fa-shopping-basket"></i>
        @if($gioTuChon && count($gioTuChon) > 0)
        <span class="cart-toggle-count" id="cartToggleCount">{{ array_sum(array_column($gioTuChon, 'so_luong')) }}</span>
        @endif
    </button>

    <!-- Floating Cart -->
    <div class="floating-cart" id="floatingCart">
        <h3>Giỏ Tự Chọn</h3>
        <div id="cart-items-container">
            @if($gioTuChon && count($gioTuChon) > 0)
                @foreach($gioTuChon as $item)
                <div class="cart-item" data-variant-id="{{ $item['ma_bien_the'] }}">
                    <img src="{{ $item['hinh_anh'] ? asset('storage/' . $item['hinh_anh']) : asset('images/default-product.jpg') }}" 
                         alt="{{ $item['ten_san_pham'] }}" 
                         class="cart-item-image">
                    <div class="cart-item-info">
                        <div class="cart-item-name">{{ $item['ten_san_pham'] }}</div>
                        <div class="cart-item-details">
                            {{ $item['kich_co'] }} - {{ number_format($item['gia']) }}₫ x {{ $item['so_luong'] }}
                        </div>
                    </div>
                    <button class="cart-item-remove" onclick="removeFromCart({{ $item['ma_bien_the'] }})">×</button>
                </div>
                @endforeach
            @else
                <div class="empty-cart-message">
                    Giỏ tự chọn trống
                </div>
            @endif
        </div>

        <div class="cart-total">
            <div class="cart-total-label">Tổng cộng:</div>
            <div class="cart-total-amount">{{ number_format($tongTien ?? 0) }}₫</div>
        </div>

        <div class="cart-actions">
            <button class="cart-btn cart-btn-primary" onclick="addAllToCart()" 
                    {{ !$gioTuChon || count($gioTuChon) == 0 ? 'disabled' : '' }}>
                Thêm vào Giỏ Hàng
            </button>
            <button class="cart-btn cart-btn-secondary" onclick="clearCart()"
                    {{ !$gioTuChon || count($gioTuChon) == 0 ? 'disabled' : '' }}>
                Xóa Tất Cả
            </button>
        </div>
    </div>

    <!-- Category Tabs -->
    <div class="category-tabs">
        <div class="category-tab active" data-category="all">Tất cả</div>
        @foreach($danhMuc as $dm)
        <div class="category-tab" data-category="{{ $dm->ma_danh_muc }}">{{ $dm->ten_danh_muc }}</div>
        @endforeach
    </div>

    <!-- Products Grid -->
    <div class="products-grid">
        @foreach($danhMuc as $dm)
        <div class="category-group" data-category-id="{{ $dm->ma_danh_muc }}">
            <h3>{{ $dm->ten_danh_muc }}</h3>
            <div class="category-products">
                @if(isset($sanPhamTheoDanhMuc[$dm->ma_danh_muc]) && count($sanPhamTheoDanhMuc[$dm->ma_danh_muc]) > 0)
                    @foreach($sanPhamTheoDanhMuc[$dm->ma_danh_muc] as $sp)
                        @php
                            $bienThe = $sp->bienThes->first();
                            $giaHienThi = $bienThe ? $bienThe->gia : 0;
                            $soLuongTon = $bienThe ? $bienThe->so_luong_ton : 0;
                            $hinhAnh = $sp->hinh_anh ? asset('storage/' . $sp->hinh_anh) : asset('images/default-product.jpg');
                        @endphp
                        <div class="ingredient-card" data-product-id="{{ $sp->ma_san_pham }}" 
                             data-variant-id="{{ $bienThe ? $bienThe->ma_bien_the : '' }}"
                             data-price="{{ $giaHienThi }}"
                             data-stock="{{ $soLuongTon }}">
                            <div class="ingredient-card-title">
                                <h4>{{ $sp->ten_san_pham }}</h4>
                            </div>
                            <div class="ingredient-card-image-wrapper">
                                <img src="{{ $hinhAnh }}" alt="{{ $sp->ten_san_pham }}" class="ingredient-card-image">
                            </div>
                            <div class="ingredient-card-box">
                                <div class="ingredient-price">{{ number_format($giaHienThi) }}₫</div>
                                @if($bienThe)
                                <div class="quantity-controls">
                                    <button class="quantity-btn" onclick="updateQuantity({{ $bienThe->ma_bien_the }}, -1)">−</button>
                                    <div class="quantity-display" data-variant="{{ $bienThe->ma_bien_the }}">
                                        @if($gioTuChon && isset($gioTuChon[$bienThe->ma_bien_the]))
                                            {{ $gioTuChon[$bienThe->ma_bien_the]['so_luong'] }}
                                        @else
                                            0
                                        @endif
                                    </div>
                                    <button class="quantity-btn" onclick="updateQuantity({{ $bienThe->ma_bien_the }}, 1)">+</button>
                                </div>
                                @else
                                <div style="color: white; text-align: center; font-size: 0.9rem;">Hết hàng</div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="grid-column: 1 / -1; text-align: center; color: #666; padding: 30px;">
                        Chưa có sản phẩm trong danh mục này
                    </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Toggle Cart Visibility
    let cartVisible = true;
    
    function toggleCart() {
        const cart = document.getElementById('floatingCart');
        const btn = document.getElementById('cartToggleBtn');
        
        cartVisible = !cartVisible;
        
        if (cartVisible) {
            cart.classList.remove('hidden');
            btn.classList.add('active');
        } else {
            cart.classList.add('hidden');
            btn.classList.remove('active');
        }
    }
    
    // Update cart toggle count
    function updateCartToggleCount(totalItems) {
        const countBadge = document.getElementById('cartToggleCount');
        const btn = document.getElementById('cartToggleBtn');
        
        if (totalItems > 0) {
            if (!countBadge) {
                const badge = document.createElement('span');
                badge.className = 'cart-toggle-count';
                badge.id = 'cartToggleCount';
                badge.textContent = totalItems;
                btn.appendChild(badge);
            } else {
                countBadge.textContent = totalItems;
            }
        } else {
            if (countBadge) {
                countBadge.remove();
            }
        }
    }

    // Category Tab Filtering
    document.querySelectorAll('.category-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const category = this.dataset.category;
            
            // Show/hide category groups
            document.querySelectorAll('.category-group').forEach(group => {
                if (category === 'all' || group.dataset.categoryId === category) {
                    group.style.display = 'block';
                } else {
                    group.style.display = 'none';
                }
            });
        });
    });

    // Update Quantity
    function updateQuantity(variantId, change) {
        const card = document.querySelector(`.ingredient-card[data-variant-id="${variantId}"]`);
        if (!card) return;
        
        const quantityDisplay = card.querySelector(`.quantity-display[data-variant="${variantId}"]`);
        if (!quantityDisplay) return;
        
        const currentQty = parseInt(quantityDisplay.textContent) || 0;
        const stock = parseInt(card.dataset.stock) || 0;
        const newQty = Math.max(0, Math.min(stock, currentQty + change));

        // Không cho phép vượt quá tồn kho hoặc giảm xuống dưới 0
        if (newQty < 0 || newQty > stock) {
            if (newQty > stock) {
                alert('Số lượng vượt quá tồn kho!');
            }
            return;
        }

        // Send AJAX request
        fetch('{{ route("tu-chon.update-session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ma_bien_the: variantId,
                so_luong: newQty
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity display
                quantityDisplay.textContent = newQty;
                
                // Update cart
                updateCartDisplay(data.cart, data.tong_tien);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi cập nhật giỏ hàng');
        });
    }

    // Remove from Cart
    function removeFromCart(variantId) {
        if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;
        
        // Send AJAX request to set quantity to 0
        fetch('{{ route("tu-chon.update-session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                ma_bien_the: variantId,
                so_luong: 0
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update quantity display in card
                const card = document.querySelector(`.ingredient-card[data-variant-id="${variantId}"]`);
                if (card) {
                    const quantityDisplay = card.querySelector(`.quantity-display[data-variant="${variantId}"]`);
                    if (quantityDisplay) {
                        quantityDisplay.textContent = '0';
                    }
                }
                
                // Update cart
                updateCartDisplay(data.cart, data.tong_tien);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }

    // Update Cart Display
    function updateCartDisplay(cartItems, totalAmount) {
        const container = document.getElementById('cart-items-container');
        let totalItems = 0;
        
        if (Object.keys(cartItems).length === 0) {
            container.innerHTML = '<div class="empty-cart-message">Giỏ tự chọn trống</div>';
            document.querySelectorAll('.cart-btn').forEach(btn => btn.disabled = true);
        } else {
            let html = '';
            Object.values(cartItems).forEach(item => {
                totalItems += item.so_luong;
                html += `
                    <div class="cart-item" data-variant-id="${item.ma_bien_the}">
                        <img src="${item.hinh_anh ? '/storage/' + item.hinh_anh : '/images/default-product.jpg'}" 
                             alt="${item.ten_san_pham}" 
                             class="cart-item-image">
                        <div class="cart-item-info">
                            <div class="cart-item-name">${item.ten_san_pham}</div>
                            <div class="cart-item-details">
                                ${item.kich_co} - ${parseInt(item.gia).toLocaleString('vi-VN')}₫ x ${item.so_luong}
                            </div>
                        </div>
                        <button class="cart-item-remove" onclick="removeFromCart(${item.ma_bien_the})">×</button>
                    </div>
                `;
            });
            container.innerHTML = html;
            document.querySelectorAll('.cart-btn').forEach(btn => btn.disabled = false);
        }

        // Update total
        document.querySelector('.cart-total-amount').textContent = parseInt(totalAmount).toLocaleString('vi-VN') + '₫';
        
        // Update toggle button count
        updateCartToggleCount(totalItems);
    }

    // Add All to Cart
    function addAllToCart() {
        if (!confirm('Bạn có chắc muốn thêm tất cả vào giỏ hàng chính?')) return;

        fetch('{{ route("tu-chon.add-to-cart") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                // Reset all quantity displays
                document.querySelectorAll('.quantity-display').forEach(display => {
                    display.textContent = '0';
                });
                // Clear cart display
                updateCartDisplay({}, 0);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
        });
    }

    // Clear Cart
    function clearCart() {
        if (!confirm('Bạn có chắc muốn xóa tất cả?')) return;

        fetch('{{ route("tu-chon.clear-session") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset all quantity displays
                document.querySelectorAll('.quantity-display').forEach(display => {
                    display.textContent = '0';
                });
                // Clear cart display
                updateCartDisplay({}, 0);
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
    }
</script>
@endsection
