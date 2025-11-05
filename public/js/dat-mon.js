/**
 * Dat Mon Page JavaScript
 * Advanced functionality for the ordering page
 */

class DatMonPage {
    constructor() {
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupLazyLoading();
        this.setupSearchSuggestions();
        // this.setupQuickActions(); // Comment để xóa 3 nút quick actions
        
        // Xóa quick actions nếu có tồn tại từ cache hoặc source khác
        $('.quick-actions').remove();
        $('#scroll-top, #toggle-view, #quick-cart').remove();
        
        // Xóa wishlist và compare nếu có tồn tại
        $('.wishlist-btn').remove();
        $('.compare-checkbox').remove();
        $('.compare-label').remove();
        $('.compare-section').remove();
        
        this.setupCartSummary();
        // this.setupWishlist(); // Đã bỏ theo yêu cầu
        // this.setupProductCompare(); // Đã bỏ theo yêu cầu
        this.setupAdvancedFilters();
        this.setupKeyboardShortcuts();
    }

    setupEventListeners() {
        // Quantity controls
        $(document).on('click', '.quantity-btn', this.handleQuantityChange.bind(this));
        
        // Add to cart
        $(document).on('click', '.add-to-cart-btn', this.handleAddToCart.bind(this));
        
        // Sort change
        $('#sort-select').on('change', this.handleSortChange.bind(this));
        
        // Search input
        $('#search').on('input', this.handleSearchInput.bind(this));
        
        // Filter form
        $('.filter-form').on('submit', this.handleFilterSubmit.bind(this));
        
        // Wishlist - Đã bỏ theo yêu cầu
        // $(document).on('click', '.wishlist-btn', this.handleWishlist.bind(this));
        
        // Product compare - Đã bỏ theo yêu cầu
        // $(document).on('click', '.compare-checkbox', this.handleCompare.bind(this));
        
        // Advanced filters toggle
        $('.toggle-filters').on('click', this.toggleAdvancedFilters.bind(this));
        
        // Price range slider
        this.setupPriceRangeSlider();
        
        // Window events
        $(window).on('scroll', this.handleScroll.bind(this));
        $(window).on('resize', this.handleResize.bind(this));
    }

    handleQuantityChange(e) {
        const button = $(e.currentTarget);
        const action = button.data('action');
        const quantityInput = button.siblings('.quantity-input');
        let currentValue = parseInt(quantityInput.val()) || 1;
        const maxValue = parseInt(quantityInput.attr('max')) || 999;
        
        if (action === 'plus' && currentValue < maxValue) {
            quantityInput.val(currentValue + 1);
        } else if (action === 'minus' && currentValue > 1) {
            quantityInput.val(currentValue - 1);
        }
        
        // Animation
        quantityInput.addClass('quantity-updated');
        setTimeout(() => quantityInput.removeClass('quantity-updated'), 300);
    }

    async handleAddToCart(e) {
        const button = $(e.currentTarget);
        const productId = button.data('product-id');
        const productCard = button.closest('.product-card');
        const quantityInput = button.closest('.product-actions').find('.quantity-input');
        const quantity = parseInt(quantityInput.val()) || 1;
        
        // Lấy size đã chọn
        const sizeSelect = productCard.find('.size-select');
        const selectedVariant = sizeSelect.val();
        
        if (!selectedVariant) {
            this.showErrorNotification('Vui lòng chọn size sản phẩm');
            return;
        }
        
        // Disable button và thêm loading
        button.prop('disabled', true);
        const originalText = button.html();
        button.html('<i class="fas fa-spinner fa-spin"></i> Đang thêm...');
        
        try {
            const response = await $.ajax({
                url: window.routes.addToCart,
                method: 'POST',
                data: {
                    ma_san_pham: productId,
                    ma_bien_the: selectedVariant,
                    so_luong: quantity,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            if (response.success) {
                this.showSuccessNotification(response.message);
                this.updateCartCount();
                this.updateCartSummary();
                
                // Reset số lượng về 1
                quantityInput.val(1);
                
                // Animation cho button
                button.removeClass('btn-primary').addClass('btn-success');
                button.html('<i class="fas fa-check"></i> Đã thêm!');
                
                setTimeout(() => {
                    button.removeClass('btn-success').addClass('btn-primary');
                    button.html(originalText);
                }, 2000);
                
                // Add to recent products
                this.addToRecentProducts(productId);
                
            } else {
                this.showErrorNotification(response.message);
            }
        } catch (xhr) {
            let message = 'Có lỗi xảy ra, vui lòng thử lại';
            
            if (xhr.status === 401) {
                message = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng';
                setTimeout(() => {
                    window.location.href = window.routes.login;
                }, 2000);
            }
            
            this.showErrorNotification(message);
        } finally {
            button.prop('disabled', false);
            setTimeout(() => {
                if (button.html().includes('fa-spinner') || button.html().includes('Đang thêm')) {
                    button.html(originalText);
                }
            }, 1000);
        }
    }

    handleSortChange(e) {
        const sortValue = $(e.target).val();
        const url = new URL(window.location.href);
        
        if (sortValue) {
            url.searchParams.set('sort', sortValue);
        } else {
            url.searchParams.delete('sort');
        }
        
        // Show loading
        this.showLoadingOverlay();
        
        window.location.href = url.toString();
    }

    handleSearchInput(e) {
        const query = $(e.target).val();
        
        if (query.length >= 2) {
            this.showSearchSuggestions(query);
        } else {
            this.hideSearchSuggestions();
        }
    }

    handleFilterSubmit(e) {
        const form = $(e.currentTarget);
        const submitBtn = form.find('.filter-btn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang tìm...');
        
        // Add filter tags
        this.updateFilterTags(new FormData(form[0]));
        
        setTimeout(() => {
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);
        }, 5000);
    }

    setupLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    }

    setupSearchSuggestions() {
        // Create suggestions container
        const searchContainer = $('#search').parent();
        searchContainer.addClass('position-relative');
        
        if (!$('.search-suggestions').length) {
            searchContainer.append('<div class="search-suggestions"></div>');
        }
    }

    showSearchSuggestions(query) {
        // Mock suggestions - replace with actual API call
        const suggestions = [
            'Bánh mì thịt nướng',
            'Phở bò',
            'Cơm tấm',
            'Bún bò Huế',
            'Bánh xèo'
        ].filter(item => item.toLowerCase().includes(query.toLowerCase()));
        
        const suggestionsHtml = suggestions.map(item => 
            `<div class="search-suggestion" data-suggestion="${item}">${item}</div>`
        ).join('');
        
        $('.search-suggestions').html(suggestionsHtml).show();
        
        // Handle suggestion click
        $('.search-suggestion').on('click', (e) => {
            const suggestion = $(e.target).data('suggestion');
            $('#search').val(suggestion);
            this.hideSearchSuggestions();
        });
    }

    hideSearchSuggestions() {
        $('.search-suggestions').hide();
    }

    setupQuickActions() {
        console.log('setupQuickActions called - but commented out');
        // Đã xóa 3 nút: lên đầu trang, thay đổi hiển thị và giỏ hàng nhanh theo yêu cầu
        // Comment out toàn bộ function này
        /*
        if (!$('.quick-actions').length) {
            $('body').append(`
                <div class="quick-actions">
                    <button class="quick-action-btn" id="scroll-top" title="Lên đầu trang">
                        <i class="fas fa-arrow-up"></i>
                        <span class="tooltip">Lên đầu trang</span>
                    </button>
                    <button class="quick-action-btn" id="toggle-view" title="Thay đổi hiển thị">
                        <i class="fas fa-th"></i>
                        <span class="tooltip">Thay đổi hiển thị</span>
                    </button>
                    <button class="quick-action-btn" id="quick-cart" title="Giỏ hàng nhanh">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="tooltip">Giỏ hàng nhanh</span>
                    </button>
                </div>
            `);
        }
        
        // Scroll to top
        $('#scroll-top').on('click', () => {
            $('html, body').animate({ scrollTop: 0 }, 500);
        });
        
        // Toggle view
        $('#toggle-view').on('click', this.toggleProductView.bind(this));
        
        // Quick cart
        $('#quick-cart').on('click', this.toggleQuickCart.bind(this));
        */
    }

    setupCartSummary() {
        if (!$('.cart-summary').length && window.user) {
            $('body').append(`
                <div class="cart-summary">
                    <div class="cart-summary-content">
                        <div class="cart-info">
                            <div class="cart-items-count">0 món</div>
                            <div class="cart-total">0đ</div>
                        </div>
                        <a href="${window.routes.cart}" class="view-cart-btn">
                            <i class="fas fa-shopping-cart me-2"></i>Xem giỏ hàng
                        </a>
                    </div>
                </div>
            `);
        }
        
        this.updateCartSummary();
    }

    async updateCartSummary() {
        if (!window.user) return;
        
        try {
            const response = await $.get(window.routes.cartSummary);
            
            if (response.success) {
                $('.cart-items-count').text(`${response.count} món`);
                $('.cart-total').text(response.total);
                
                if (response.count > 0) {
                    $('.cart-summary').addClass('show');
                } else {
                    $('.cart-summary').removeClass('show');
                }
            }
        } catch (error) {
            console.error('Error updating cart summary:', error);
        }
    }

    // setupWishlist() - Đã bỏ theo yêu cầu
    /*
    setupWishlist() {
        // Add wishlist buttons to product cards
        $('.product-card .product-image').each(function() {
            if (!$(this).find('.wishlist-btn').length) {
                $(this).append(`
                    <button class="wishlist-btn" data-product-id="${$(this).closest('.product-card').data('product-id')}">
                        <i class="far fa-heart"></i>
                    </button>
                `);
            }
        });
    }

    handleWishlist(e) {
        e.preventDefault();
        const button = $(e.currentTarget);
        const productId = button.data('product-id');
        const icon = button.find('i');
        
        if (button.hasClass('active')) {
            icon.removeClass('fas').addClass('far');
            button.removeClass('active');
            this.removeFromWishlist(productId);
        } else {
            icon.removeClass('far').addClass('fas');
            button.addClass('active');
            this.addToWishlist(productId);
        }
    }
    */

    // setupProductCompare() - Đã bỏ theo yêu cầu
    /*
    setupProductCompare() {
        // Add compare checkboxes to product cards
        $('.product-card .product-info').each(function() {
            if (!$(this).find('.compare-checkbox').length) {
                $(this).prepend(`
                    <label class="compare-label">
                        <input type="checkbox" class="compare-checkbox" data-product-id="${$(this).closest('.product-card').data('product-id')}">
                        <span>So sánh</span>
                    </label>
                `);
            }
        });
        
        if (!$('.compare-section').length) {
            $('body').append(`
                <div class="compare-section">
                    <div class="compare-content">
                        <div class="compare-info">
                            <span class="compare-count">0</span> sản phẩm đã chọn
                        </div>
                        <div class="compare-items"></div>
                        <button class="compare-btn">So sánh ngay</button>
                    </div>
                </div>
            `);
        }
    }

    handleCompare(e) {
        const checkbox = $(e.currentTarget);
        const productId = checkbox.data('product-id');
        const productCard = checkbox.closest('.product-card');
        
        if (checkbox.is(':checked')) {
            this.addToCompare(productId, productCard);
        } else {
            this.removeFromCompare(productId);
        }
        
        this.updateCompareSection();
    }
    */

    setupAdvancedFilters() {
        if (!$('.advanced-filters').length) {
            $('.filter-section').after(`
                <div class="advanced-filters">
                    <h5>Bộ lọc nâng cao</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <label>Đánh giá</label>
                            <div class="rating-filter">
                                <input type="radio" name="rating" value="5"> 5 sao<br>
                                <input type="radio" name="rating" value="4"> 4 sao trở lên<br>
                                <input type="radio" name="rating" value="3"> 3 sao trở lên
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Thương hiệu</label>
                            <div class="brand-filter">
                                <!-- Dynamic brand list -->
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Tình trạng</label>
                            <div class="status-filter">
                                <input type="checkbox" name="status" value="in_stock"> Còn hàng<br>
                                <input type="checkbox" name="status" value="sale"> Đang giảm giá<br>
                                <input type="checkbox" name="status" value="featured"> Nổi bật
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Khoảng giá</label>
                            <div class="price-range-slider">
                                <input type="range" id="price-min" min="0" max="1000000" step="10000">
                                <input type="range" id="price-max" min="0" max="1000000" step="10000">
                                <div class="price-values">
                                    <span id="price-min-value">0đ</span> - 
                                    <span id="price-max-value">1,000,000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
        }
        
        $('.filter-section').prepend(`
            <button type="button" class="toggle-filters">
                <i class="fas fa-sliders-h me-2"></i>Bộ lọc nâng cao
            </button>
        `);
    }

    toggleAdvancedFilters() {
        $('.advanced-filters').toggleClass('show');
        const button = $('.toggle-filters');
        const icon = button.find('i');
        
        if ($('.advanced-filters').hasClass('show')) {
            icon.removeClass('fa-sliders-h').addClass('fa-times');
            button.find('span').text('Đóng bộ lọc');
        } else {
            icon.removeClass('fa-times').addClass('fa-sliders-h');
            button.find('span').text('Bộ lọc nâng cao');
        }
    }

    setupPriceRangeSlider() {
        $('#price-min, #price-max').on('input', function() {
            const minVal = parseInt($('#price-min').val());
            const maxVal = parseInt($('#price-max').val());
            
            $('#price-min-value').text(minVal.toLocaleString() + 'đ');
            $('#price-max-value').text(maxVal.toLocaleString() + 'đ');
            
            // Update form inputs
            $('#gia_min').val(minVal);
            $('#gia_max').val(maxVal);
        });
    }

    setupKeyboardShortcuts() {
        $(document).on('keydown', (e) => {
            // Ctrl + F: Focus search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                $('#search').focus();
            }
            
            // Ctrl + Enter: Submit search
            if (e.ctrlKey && e.key === 'Enter') {
                $('.filter-form').submit();
            }
            
            // Escape: Clear search
            if (e.key === 'Escape') {
                $('#search').val('').focus();
                this.hideSearchSuggestions();
            }
        });
    }

    handleScroll() {
        const scrollTop = $(window).scrollTop();
        
        // Show/hide scroll to top button - Đã comment vì đã xóa nút
        /*
        if (scrollTop > 300) {
            $('#scroll-top').addClass('show');
        } else {
            $('#scroll-top').removeClass('show');
        }
        */
        
        // Sticky filter section
        const filterSection = $('.filter-section');
        const heroSection = $('.hero-section');
        
        if (scrollTop > heroSection.outerHeight()) {
            filterSection.addClass('sticky');
        } else {
            filterSection.removeClass('sticky');
        }
    }

    handleResize() {
        // Adjust grid layout on resize
        this.adjustGridLayout();
    }

    // Helper methods
    showSuccessNotification(message) {
        if (typeof window.showToast === 'function') {
            window.showToast('success', 'Thành công!', message);
        } else {
            this.showSimpleNotification(message, 'success');
        }
    }

    showErrorNotification(message) {
        if (typeof window.showToast === 'function') {
            window.showToast('error', 'Lỗi!', message);
        } else {
            this.showSimpleNotification(message, 'error');
        }
    }

    showSimpleNotification(message, type) {
        const notification = $(`
            <div class="simple-notification ${type}">
                <div class="notification-content">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'}"></i>
                    <span>${message}</span>
                </div>
            </div>
        `);
        
        $('body').append(notification);
        
        setTimeout(() => {
            notification.addClass('show');
        }, 100);
        
        setTimeout(() => {
            notification.removeClass('show');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    showLoadingOverlay() {
        if (!$('.loading-overlay').length) {
            $('body').append(`
                <div class="loading-overlay">
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i>
                        <p>Đang tải...</p>
                    </div>
                </div>
            `);
        }
        
        $('.loading-overlay').show();
    }

    hideLoadingOverlay() {
        $('.loading-overlay').hide();
    }

    toggleProductView() {
        const grid = $('.products-grid');
        
        if (grid.hasClass('list-view')) {
            grid.removeClass('list-view').addClass('grid-view');
            $('#toggle-view i').removeClass('fa-th-list').addClass('fa-th');
        } else {
            grid.removeClass('grid-view').addClass('list-view');
            $('#toggle-view i').removeClass('fa-th').addClass('fa-th-list');
        }
    }

    updateCartCount() {
        if (typeof window.updateCartCount === 'function') {
            window.updateCartCount();
        }
    }

    addToRecentProducts(productId) {
        let recent = JSON.parse(localStorage.getItem('recentProducts') || '[]');
        recent = recent.filter(id => id !== productId);
        recent.unshift(productId);
        recent = recent.slice(0, 10); // Keep only 10 recent items
        localStorage.setItem('recentProducts', JSON.stringify(recent));
    }

    adjustGridLayout() {
        // Implement responsive grid adjustments
        const containerWidth = $('.container').width();
        const grid = $('.products-grid');
        
        if (containerWidth < 768) {
            grid.css('grid-template-columns', '1fr');
        } else if (containerWidth < 992) {
            grid.css('grid-template-columns', 'repeat(2, 1fr)');
        } else if (containerWidth < 1200) {
            grid.css('grid-template-columns', 'repeat(3, 1fr)');
        } else {
            grid.css('grid-template-columns', 'repeat(auto-fill, minmax(280px, 1fr))');
        }
    }
}

// Initialize when document is ready
$(document).ready(function() {
    // Set up global routes (should be passed from Blade template)
    window.routes = window.routes || {
        addToCart: '/dat-mon/add-to-cart',
        cart: '/gio-hang',
        cartSummary: '/gio-hang/summary',
        login: '/dang-nhap'
    };
    
    console.log('Initializing DatMonPage...');
    
    // Initialize the page
    new DatMonPage();
    
    // Double check to remove quick actions after initialization
    setTimeout(() => {
        if ($('.quick-actions').length > 0) {
            console.log('Found quick-actions after init, removing...');
            $('.quick-actions').remove();
        }
    }, 100);
});

// Export for use in other modules
window.DatMonPage = DatMonPage;