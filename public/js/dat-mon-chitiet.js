// Dat Mon Chi Tiet JavaScript
$(document).ready(function() {
    let selectedVariant = null;
    let selectedPrice = 0;
    let maxStock = 0;

    // Xử lý chọn biến thể
    $('input[name="bien_the_id"]').on('change', function() {
        selectedVariant = $(this).val();
        selectedPrice = parseInt($(this).data('price'));
        maxStock = parseInt($(this).data('stock'));
        
        // Cập nhật thông tin số lượng
        updateQuantityInfo();
        
        // Cập nhật tổng tiền
        updateTotalPrice();
        
        // Enable/disable nút thêm vào giỏ hàng
        updateAddToCartButton();
        
        // Thêm hiệu ứng cho variant được chọn
        $('.variant-option').removeClass('bounce-in');
        $(this).next('.variant-option').addClass('bounce-in');
    });

    // Xử lý tăng/giảm số lượng
    $('#decreaseQty').on('click', function() {
        let currentQty = parseInt($('#quantityInput').val());
        if (currentQty > 1) {
            $('#quantityInput').val(currentQty - 1);
            updateTotalPrice();
        }
    });

    $('#increaseQty').on('click', function() {
        let currentQty = parseInt($('#quantityInput').val());
        if (currentQty < maxStock) {
            $('#quantityInput').val(currentQty + 1);
            updateTotalPrice();
        }
    });

    // Xử lý thay đổi số lượng trực tiếp
    $('#quantityInput').on('input', function() {
        let qty = parseInt($(this).val());
        if (isNaN(qty) || qty < 1) {
            $(this).val(1);
        } else if (qty > maxStock) {
            $(this).val(maxStock);
        }
        updateTotalPrice();
    });

    // Xử lý form thêm vào giỏ hàng
    $('#addToCartForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!selectedVariant) {
            showAlert('Vui lòng chọn kích cỡ sản phẩm!', 'warning');
            return;
        }

        const formData = {
            san_pham_id: $('input[name="san_pham_id"]').val(),
            bien_the_id: selectedVariant,
            so_luong: parseInt($('#quantityInput').val()),
            _token: $('input[name="_token"]').val()
        };

        // Hiển thị loading
        showLoadingModal();
        
        // Vô hiệu hóa form
        $('#addToCartForm input, #addToCartForm button').prop('disabled', true);

        // Gửi request
        $.ajax({
            url: '/dat-mon/add-to-cart',
            method: 'POST',
            data: formData,
            success: function(response) {
                hideLoadingModal();
                
                if (response.success) {
                    // Cập nhật số lượng giỏ hàng trong header
                    updateCartCount();
                    
                    // Hiển thị thông báo thành công
                    showAlert('Đã thêm sản phẩm vào giỏ hàng thành công!', 'success');
                    
                    // Thêm hiệu ứng thành công
                    $('#addToCartBtn').addClass('success-state');
                    setTimeout(() => {
                        $('#addToCartBtn').removeClass('success-state');
                    }, 2000);
                    
                    // Reset form
                    resetForm();
                } else {
                    showAlert(response.message || 'Có lỗi xảy ra khi thêm vào giỏ hàng!', 'error');
                }
            },
            error: function(xhr) {
                hideLoadingModal();
                let errorMessage = 'Có lỗi xảy ra khi thêm vào giỏ hàng!';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 401) {
                    errorMessage = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng!';
                }
                
                showAlert(errorMessage, 'error');
            },
            complete: function() {
                // Kích hoạt lại form
                $('#addToCartForm input, #addToCartForm button').prop('disabled', false);
            }
        });
    });

    // Cập nhật thông tin số lượng
    function updateQuantityInfo() {
        const stockInfo = $('#stockInfo');
        const quantityInput = $('#quantityInput');
        
        if (selectedVariant && maxStock > 0) {
            stockInfo.text(`Còn ${maxStock} sản phẩm`).removeClass('text-muted text-danger').addClass('text-success');
            quantityInput.attr('max', maxStock);
            
            // Điều chỉnh số lượng hiện tại nếu vượt quá tồn kho
            let currentQty = parseInt(quantityInput.val());
            if (currentQty > maxStock) {
                quantityInput.val(maxStock);
            }
        } else if (selectedVariant && maxStock === 0) {
            stockInfo.text('Hết hàng').removeClass('text-muted text-success').addClass('text-danger');
            quantityInput.val(0).attr('max', 0);
        } else {
            stockInfo.text('Vui lòng chọn kích cỡ').removeClass('text-success text-danger').addClass('text-muted');
            quantityInput.val(1).attr('max', 1);
        }
    }

    // Cập nhật tổng tiền
    function updateTotalPrice() {
        const quantity = parseInt($('#quantityInput').val()) || 0;
        const totalPrice = selectedPrice * quantity;
        
        if (selectedVariant && totalPrice > 0) {
            $('#totalPrice').text(formatPrice(totalPrice) + 'đ').addClass('fade-in');
        } else {
            $('#totalPrice').text('0đ').removeClass('fade-in');
        }
    }

    // Cập nhật trạng thái nút thêm vào giỏ hàng
    function updateAddToCartButton() {
        const addToCartBtn = $('#addToCartBtn');
        const quantity = parseInt($('#quantityInput').val()) || 0;
        
        if (selectedVariant && maxStock > 0 && quantity > 0) {
            addToCartBtn.prop('disabled', false).removeClass('error-state');
        } else {
            addToCartBtn.prop('disabled', true).addClass('error-state');
        }
    }

    // Hiển thị modal loading
    function showLoadingModal() {
        $('#loadingModal').modal('show');
    }

    // Ẩn modal loading
    function hideLoadingModal() {
        $('#loadingModal').modal('hide');
    }

    // Hiển thị thông báo
    function showAlert(message, type) {
        const alertClass = type === 'success' ? 'alert-success' : 
                          type === 'warning' ? 'alert-warning' : 'alert-danger';
        
        const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                <strong>${message}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);
        
        $('body').append(alert);
        
        // Tự động ẩn sau 5 giây
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }

    // Cập nhật số lượng giỏ hàng
    function updateCartCount() {
        $.get('/gio-hang/count', function(data) {
            if (data.count !== undefined) {
                $('.cart-count').text(data.count);
                
                // Thêm hiệu ứng bounce cho icon giỏ hàng
                $('.cart-icon').addClass('bounce-in');
                setTimeout(() => {
                    $('.cart-icon').removeClass('bounce-in');
                }, 600);
            }
        }).fail(function() {
            console.log('Không thể cập nhật số lượng giỏ hàng');
        });
    }

    // Reset form
    function resetForm() {
        $('input[name="bien_the_id"]').prop('checked', false);
        $('.variant-option').removeClass('bounce-in success-state');
        $('#quantityInput').val(1);
        selectedVariant = null;
        selectedPrice = 0;
        maxStock = 0;
        
        updateQuantityInfo();
        updateTotalPrice();
        updateAddToCartButton();
    }

    // Format giá tiền
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    // Xử lý responsive cho tabs
    function handleResponsiveTabs() {
        if ($(window).width() < 768) {
            $('.nav-tabs .nav-link').addClass('btn-sm');
        } else {
            $('.nav-tabs .nav-link').removeClass('btn-sm');
        }
    }

    // Khởi tạo
    handleResponsiveTabs();
    $(window).on('resize', handleResponsiveTabs);

    // Lazy loading cho hình ảnh
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('fade-in');
                    observer.unobserve(img);
                }
            });
        });

        $('.product-main-image').each(function() {
            imageObserver.observe(this);
        });
    }

    // Smooth scroll cho breadcrumb links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $($(this).attr('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });

    // Keyboard navigation cho variant selection
    $(document).on('keydown', function(e) {
        if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            const variants = $('input[name="bien_the_id"]');
            const currentIndex = variants.index(variants.filter(':checked'));
            let newIndex;
            
            if (e.key === 'ArrowLeft') {
                newIndex = currentIndex > 0 ? currentIndex - 1 : variants.length - 1;
            } else {
                newIndex = currentIndex < variants.length - 1 ? currentIndex + 1 : 0;
            }
            
            variants.eq(newIndex).prop('checked', true).trigger('change');
        }
    });
});