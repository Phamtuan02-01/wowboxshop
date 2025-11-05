@extends('layouts.app')

@section('title', 'Giỏ hàng - WowBox Shop')

@section('styles')
<link href="{{ asset('css/giohang.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="cart-container">
    <div class="cart-header">
        <h1 class="cart-title">GIỎ HÀNG</h1>
    </div>

    @if($chiTietGioHang->count() > 0)
        <div class="cart-content">
            <!-- Danh sách sản phẩm -->
            <div class="cart-items">
                <div class="cart-section-header">
                    <h3>SẢN PHẨM</h3>
                    <h3 class="quantity-header">SỐ LƯỢNG</h3>
                </div>

                @foreach($chiTietGioHang as $item)
                    <div class="cart-item" data-id="{{ $item->ma_chi_tiet_gio_hang }}">
                        <div class="item-checkbox">
                            <input type="checkbox" class="item-select" checked>
                        </div>
                        
                        <div class="item-image">
                            <img src="{{ $item->sanPham && $item->sanPham->hinh_anh ? asset('images/products/' . $item->sanPham->hinh_anh) : asset('images/default-product.png') }}" 
                                 alt="{{ $item->sanPham ? $item->sanPham->ten_san_pham : 'Sản phẩm' }}"
                                 onerror="this.src='{{ asset('images/default-product.png') }}'">
                        </div>
                        
                        <div class="item-details">
                            <h4 class="item-name">{{ strtoupper($item->sanPham->ten_san_pham) }}</h4>
                            
                            <div class="item-price">
                                @if(isset($item->promotion_info) && $item->promotion_info['has_promotion'])
                                    <!-- Có khuyến mãi -->
                                    <span class="original-price">{{ number_format($item->gia_goc, 0, ',', '.') }} VND</span>
                                    <span class="discounted-price">{{ number_format($item->gia_khuyen_mai, 0, ',', '.') }} VND</span>
                                    <span class="promotion-badge">
                                        <i class="fas fa-tag"></i>
                                        @if($item->promotion_info['promotion_type'] == 'percent')
                                            -{{ $item->promotion_info['discount_percentage'] }}%
                                        @else
                                            -{{ number_format($item->gia_goc - $item->gia_khuyen_mai, 0, ',', '.') }}đ
                                        @endif
                                    </span>
                                @else
                                    <!-- Không có khuyến mãi -->
                                    <span class="current-price">{{ number_format($item->gia_goc, 0, ',', '.') }} VND</span>
                                @endif
                                <span class="calories"> - ({{ $item->bienThe->calo ?? 0 }} CAL)</span>
                            </div>
                            
                            @if(isset($item->promotion_info) && $item->promotion_info['has_promotion'])
                                <div class="promotion-name">
                                    <i class="fas fa-gift"></i> {{ $item->promotion_info['promotion_name'] }}
                                </div>
                            @endif
                            
                            @if($item->bienThe && $item->bienThe->kich_thuoc)
                                <div class="item-size">
                                    Kích thước: {{ $item->bienThe->kich_thuoc }}
                                </div>
                            @endif
                            
                            <div class="item-note">
                                <input type="text" placeholder="VD: ít muối" class="note-input">
                            </div>
                        </div>
                        
                        <div class="item-quantity">
                            <div class="quantity-controls">
                                <button type="button" class="qty-btn minus" onclick="updateQuantity({{ $item->ma_chi_tiet_gio_hang }}, -1)">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" value="{{ $item->so_luong }}" min="1" class="qty-input" 
                                       onchange="updateQuantityInput({{ $item->ma_chi_tiet_gio_hang }}, this.value)">
                                <button type="button" class="qty-btn plus" onclick="updateQuantity({{ $item->ma_chi_tiet_gio_hang }}, 1)">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            
                            <button type="button" class="remove-btn" onclick="removeItem({{ $item->ma_chi_tiet_gio_hang }})">
                                XÓA
                            </button>
                            
                            <div class="item-total">
                                @if(isset($item->tiet_kiem) && $item->tiet_kiem > 0)
                                    <div class="item-savings">
                                        Tiết kiệm: {{ number_format($item->tiet_kiem, 0, ',', '.') }} VND
                                    </div>
                                @endif
                                <span class="item-total-price" id="total-{{ $item->ma_chi_tiet_gio_hang }}">
                                    Tổng tiền: {{ number_format($item->gia_khuyen_mai * $item->so_luong, 0, ',', '.') }} VND
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="cart-summary">
                <div class="summary-content">
                    <div class="summary-item">
                        <span>TỔNG SỐ MÓN</span>
                        <span class="summary-value" id="total-items">{{ $chiTietGioHang->sum('so_luong') }} MÓN</span>
                    </div>
                    
                    @if($tongTietKiem > 0)
                        <div class="summary-item">
                            <span>Tổng tiền gốc:</span>
                            <span class="summary-value original-amount">{{ number_format($tongTienGoc, 0, ',', '.') }} VND</span>
                        </div>
                        
                        <div class="summary-item promotion-savings">
                            <span><i class="fas fa-gift"></i> Tiết kiệm khuyến mãi:</span>
                            <span class="summary-value savings-amount">-{{ number_format($tongTietKiem, 0, ',', '.') }} VND</span>
                        </div>
                    @endif
                    
                    <div class="summary-item">
                        <span>Tổng tiền:</span>
                        <span class="summary-value" id="subtotal">{{ number_format($tongTien, 0, ',', '.') }} VND</span>
                    </div>
                    
                    <div class="summary-item">
                        <span>Phí vận chuyển:</span>
                        <span class="summary-value">{{ number_format($phiVanChuyen, 0, ',', '.') }} VND</span>
                    </div>
                    
                    <div class="summary-note">
                        <small>Đã bao gồm thuế VAT</small>
                    </div>
                    
                    <div class="summary-total">
                        <span>Tổng hóa đơn:</span>
                        <span class="total-price" id="grand-total">{{ number_format($tongHoaDon, 0, ',', '.') }} VND</span>
                    </div>
                    
                    <div class="delivery-note">
                        <small>Hóa đơn của bạn đã đủ điều kiện để miễn phí vận chuyển</small>
                    </div>
                    
                    <button type="button" class="checkout-btn" onclick="checkout()">
                        THANH TOÁN
                    </button>
                    
                    <div class="payment-methods">
                        <a href="#" class="payment-link">Lựa trọn thanh toán</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Giỏ hàng trống -->
        <div class="empty-cart">
            <div class="empty-cart-content">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2>GIỎ HÀNG HIỆN ĐANG TRỐNG</h2>
                <p>Bạn hiện chưa có sản phẩm nào trong giỏ hàng</p>
                <a href="{{ route('trangchu') }}" class="continue-shopping-btn">
                    TIẾP TỤC MUA HÀNG
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
// Cập nhật số lượng sản phẩm
function updateQuantity(id, change) {
    const qtyInput = document.querySelector(`[data-id="${id}"] .qty-input`);
    let currentQty = parseInt(qtyInput.value);
    let newQty = Math.max(1, currentQty + change);
    
    qtyInput.value = newQty;
    updateQuantityAjax(id, newQty);
}

function updateQuantityInput(id, qty) {
    qty = Math.max(1, parseInt(qty));
    updateQuantityAjax(id, qty);
}

function updateQuantityAjax(id, qty) {
    // Hiển thị loading
    const totalElement = document.getElementById(`total-${id}`);
    const originalText = totalElement.textContent;
    totalElement.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang cập nhật...';
    
    // Disable buttons
    const cartItem = document.querySelector(`[data-id="${id}"]`);
    const buttons = cartItem.querySelectorAll('.qty-btn, .remove-btn');
    buttons.forEach(btn => btn.disabled = true);
    
    fetch(`{{ route('giohang.capnhat', '') }}/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ so_luong: qty })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Cập nhật tổng tiền của item
            let totalHTML = `Tổng tiền: ${data.tong_tien_item}`;
            
            // Hiển thị tiết kiệm nếu có
            if (data.has_promotion && data.tiet_kiem && data.tiet_kiem !== '0 VND') {
                const savingsElement = cartItem.querySelector('.item-savings');
                if (savingsElement) {
                    savingsElement.innerHTML = `Tiết kiệm: ${data.tiet_kiem}`;
                } else {
                    // Tạo element tiết kiệm mới
                    const itemTotal = cartItem.querySelector('.item-total');
                    const savingsDiv = document.createElement('div');
                    savingsDiv.className = 'item-savings';
                    savingsDiv.innerHTML = `Tiết kiệm: ${data.tiet_kiem}`;
                    itemTotal.insertBefore(savingsDiv, totalElement);
                }
            }
            
            totalElement.innerHTML = totalHTML;
            
            // Cập nhật tổng tiền giỏ hàng
            calculateTotal();
            
            // Cập nhật số lượng trên header
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
            
            // Hiển thị thông báo thành công (nếu có hàm showFlashMessage)
            if (typeof showFlashMessage === 'function') {
                showFlashMessage('success', 'Đã cập nhật số lượng');
            }
        } else {
            // Khôi phục giá trị cũ nếu lỗi
            totalElement.textContent = originalText;
            if (typeof showFlashMessage === 'function') {
                showFlashMessage('error', data.message || 'Có lỗi xảy ra');
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        totalElement.textContent = originalText;
        if (typeof showFlashMessage === 'function') {
            showFlashMessage('error', 'Có lỗi xảy ra khi cập nhật số lượng');
        } else {
            alert('Có lỗi xảy ra khi cập nhật số lượng');
        }
    })
    .finally(() => {
        // Enable lại buttons
        buttons.forEach(btn => btn.disabled = false);
    });
}

// Xóa sản phẩm với confirmation và AJAX
function removeItem(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        // Hiển thị loading
        const cartItem = document.querySelector(`[data-id="${id}"]`);
        cartItem.style.opacity = '0.5';
        cartItem.style.pointerEvents = 'none';
        
        // Xóa bằng AJAX
        fetch(`{{ route('giohang.xoa.ajax', '') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Animation xóa
                cartItem.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Kiểm tra nếu giỏ hàng trống
                    const remainingItems = document.querySelectorAll('.cart-item');
                    if (remainingItems.length === 0) {
                        // Reload trang để hiển thị empty cart
                        window.location.reload();
                    } else {
                        // Cập nhật tổng tiền
                        calculateTotal();
                    }
                    
                    // Cập nhật số lượng trên header
                    if (typeof updateCartCount === 'function') {
                        updateCartCount();
                    }
                }, 300);
                
                // Hiển thị thông báo
                if (typeof showFlashMessage === 'function') {
                    showFlashMessage('success', data.message);
                }
            } else {
                // Khôi phục trạng thái nếu lỗi
                cartItem.style.opacity = '1';
                cartItem.style.pointerEvents = 'auto';
                
                if (typeof showFlashMessage === 'function') {
                    showFlashMessage('error', data.message || 'Có lỗi xảy ra');
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Khôi phục trạng thái nếu lỗi
            cartItem.style.opacity = '1';
            cartItem.style.pointerEvents = 'auto';
            
            if (typeof showFlashMessage === 'function') {
                showFlashMessage('error', 'Có lỗi xảy ra khi xóa sản phẩm');
            } else {
                alert('Có lỗi xảy ra khi xóa sản phẩm');
            }
        });
    }
}

// Tính tổng tiền với animation
function calculateTotal() {
    let subtotal = 0;
    let totalItems = 0;
    const checkedItems = document.querySelectorAll('.item-select:checked');
    
    checkedItems.forEach(checkbox => {
        const cartItem = checkbox.closest('.cart-item');
        const priceText = cartItem.querySelector('.item-total-price').textContent;
        const price = parseInt(priceText.replace(/[^\d]/g, ''));
        const qty = parseInt(cartItem.querySelector('.qty-input').value);
        
        subtotal += price;
        totalItems += qty;
    });
    
    const shippingFee = {{ $phiVanChuyen }};
    const total = subtotal + shippingFee;
    
    // Animate số thay đổi
    animateNumber('subtotal', subtotal, ' VND');
    animateNumber('grand-total', total, ' VND');
    
    // Cập nhật số lượng món
    document.getElementById('total-items').textContent = totalItems + ' MÓN';
    
    // Cập nhật trạng thái nút thanh toán
    const checkoutBtn = document.querySelector('.checkout-btn');
    if (checkedItems.length === 0) {
        checkoutBtn.disabled = true;
        checkoutBtn.textContent = 'CHỌN SẢN PHẨM ĐỂ THANH TOÁN';
        checkoutBtn.style.opacity = '0.6';
    } else {
        checkoutBtn.disabled = false;
        checkoutBtn.textContent = 'THANH TOÁN';
        checkoutBtn.style.opacity = '1';
    }
}

// Animation cho số tiền
function animateNumber(elementId, targetNumber, suffix = '') {
    const element = document.getElementById(elementId);
    const currentNumber = parseInt(element.textContent.replace(/[^\d]/g, '')) || 0;
    const difference = targetNumber - currentNumber;
    const duration = 500; // 0.5 giây
    const steps = 20;
    const stepValue = difference / steps;
    let currentStep = 0;
    
    const timer = setInterval(() => {
        currentStep++;
        const newValue = Math.round(currentNumber + (stepValue * currentStep));
        element.textContent = newValue.toLocaleString('vi-VN') + suffix;
        
        if (currentStep >= steps) {
            clearInterval(timer);
            element.textContent = targetNumber.toLocaleString('vi-VN') + suffix;
        }
    }, duration / steps);
}

// Thanh toán
function checkout() {
    const checkedItems = document.querySelectorAll('.item-select:checked');
    
    if (checkedItems.length === 0) {
        showFlashMessage('warning', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán');
        return;
    }
    
    // Hiển thị loading cho nút thanh toán
    const checkoutBtn = document.querySelector('.checkout-btn');
    const originalText = checkoutBtn.textContent;
    checkoutBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ĐANG XỬ LÝ...';
    checkoutBtn.disabled = true;
    
    // Redirect to checkout page
    window.location.href = '{{ route("thanh-toan.index") }}';
}

// Select/Deselect all items
function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById('select-all');
    const itemCheckboxes = document.querySelectorAll('.item-select');
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
    
    calculateTotal();
}

// Xử lý sự kiện khi trang được load
document.addEventListener('DOMContentLoaded', function() {
    // Thêm checkbox "Chọn tất cả"
    const sectionHeader = document.querySelector('.cart-section-header');
    if (sectionHeader) {
        const selectAllDiv = document.createElement('div');
        selectAllDiv.innerHTML = `
            <label class="select-all-label">
                <input type="checkbox" id="select-all" onchange="toggleSelectAll()" checked>
                <span>Chọn tất cả</span>
            </label>
        `;
        sectionHeader.insertBefore(selectAllDiv, sectionHeader.firstChild);
    }
    
    // Xử lý checkbox items
    const checkboxes = document.querySelectorAll('.item-select');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            calculateTotal();
            
            // Cập nhật checkbox "Chọn tất cả"
            const selectAllCheckbox = document.getElementById('select-all');
            const checkedItems = document.querySelectorAll('.item-select:checked');
            selectAllCheckbox.checked = checkedItems.length === checkboxes.length;
        });
    });
    
    // Tính tổng ban đầu
    calculateTotal();
    
    // Thêm keyboard support cho quantity input
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const id = this.closest('.cart-item').dataset.id;
                updateQuantityInput(id, this.value);
            }
        });
    });
});

// Auto-save note inputs
document.addEventListener('input', function(e) {
    if (e.target.classList.contains('note-input')) {
        // Debounce auto-save
        clearTimeout(e.target.saveTimeout);
        e.target.saveTimeout = setTimeout(() => {
            showFlashMessage('info', 'Ghi chú đã được lưu tự động');
        }, 1000);
    }
});
</script>

<style>
.select-all-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #2E7D32;
    cursor: pointer;
}

.qty-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.checkout-btn:disabled {
    cursor: not-allowed;
    transform: none !important;
}

.cart-item {
    transition: opacity 0.3s ease;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.item-total-price {
    transition: all 0.3s ease;
}

.item-total-price.updating {
    animation: pulse 0.5s ease-in-out;
}
</style>
@endsection