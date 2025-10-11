@extends('layouts.app')

@section('title', 'WowBox Shop - Salad tươi ngon, dinh dưỡng')

@section('styles')
<link href="{{ asset('css/homepage.css') }}" rel="stylesheet">
<link href="{{ asset('css/placeholder-images.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="homepage">
    <!-- Orange decorations -->
    <div class="orange-decoration orange-slice-1">
        <img src="{{ asset('images/trangchu/orange-slice.png') }}" alt="Orange slice" style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="orange-decoration orange-slice-2">
        <img src="{{ asset('images/trangchu/orange-slice.png') }}" alt="Orange slice" style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="orange-decoration orange-slice-3">
        <img src="{{ asset('images/trangchu/orange-slice.png') }}" alt="Orange slice" style="width: 100%; height: 100%; object-fit: contain;">
    </div>
    <div class="orange-decoration orange-slice-4">
        <img src="{{ asset('images/trangchu/orange-slice.png') }}" alt="Orange slice" style="width: 100%; height: 100%; object-fit: contain;">
    </div>

    <div class="homepage-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">CHÀO MỪNG<br>ĐẾN VỚI<br>WOWBOX SALAD</h1>
                <p class="hero-description">
                    Sự kết hợp hoàn hảo giữa món ăn sinh tố ngon miệng.
                    Xu hướng mới của thế hệ trẻ được làm mới nhất.
                    Đặt ngay để trải nghiệm món ăn tuyệt vời.
                    Hãy thử ngay hôm nay.
                </p>
                <button class="order-btn" onclick="scrollToProducts()">ĐẶT NGAY</button>
            </div>

            <div class="hero-image-section">
                <div class="circular-slider" id="circularSlider">
                    <div class="slider-track"></div>
                    
                    <div class="slide-image active">
                        <img src="{{ asset('images/trangchu/salad-1.png') }}" alt="WowBox Salad 1">
                    </div>
                    <div class="slide-image">
                        <img src="{{ asset('images/trangchu/salad-1.png') }}" alt="WowBox Salad 2">
                    </div>
                    <div class="slide-image">
                        <img src="{{ asset('images/trangchu/salad-1.png') }}" alt="WowBox Salad 3">
                    </div>
                    <div class="slide-image">
                        <img src="{{ asset('images/trangchu/salad-1.png') }}" alt="WowBox Salad 4">
                    </div>

                    <div class="slider-controls">
                        <button class="slider-btn" onclick="previousSlide()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="slider-btn" onclick="nextSlide()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="slide-indicators">
                        <div class="indicator active" onclick="goToSlide(0)"></div>
                        <div class="indicator" onclick="goToSlide(1)"></div>
                        <div class="indicator" onclick="goToSlide(2)"></div>
                        <div class="indicator" onclick="goToSlide(3)"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Nutrition Section -->
        <section class="nutrition-section">
            <div class="nutrition-image">
                <img src="{{ asset('images/trangchu/nutrition-salad.jpg') }}" alt="Bữa ăn dinh dưỡng">
            </div>
            <div class="nutrition-content">
                <h2 class="nutrition-title">BỮA ĂN<br>DINH DƯỠNG</h2>
                <p class="nutrition-description">
                    Salad của chúng tôi mang nguồn dinh dưỡng tuyệt vời dành phòng phòng 
                    phòng. Đây là sự dưỡng ưu việt nhất của những món ăn cân bằn. Với 
                    chúng tôi, bạn sẽ có được những bữa ăn thật sự dinh dưỡng.
                </p>
                <p class="nutrition-description">
                    Đến với WowBox, mỗi khách hàng sẽ chọn được cho mình 
                    những ăn khuyến mãi bạn học món này cùng với đa dạng. Với 
                    hương vị ngon WowBox được là chọn và giải thưởng một lượng ăn, 
                    WowBox đảm được với chất lượng một siêu phù hợp với món siêu
                    Xem thêm các ngon.
                </p>
                <div class="nutrition-highlight">
                    Tất cả các món ăn đều được chế biến từ nguyên liệu tươi ngon và an toàn!
                </div>
            </div>
        </section>

        <!-- Featured Products Section -->
        <section class="featured-section" id="featuredProducts">
            <h2 class="featured-title">MÓN ĂN NỔI BẬT</h2>
            
            <div class="featured-products" id="productsContainer">
                @foreach($sanPhamNoiBat as $index => $sanPham)
                    <div class="product-card" data-product="{{ $sanPham->ma_san_pham }}">
                        <div class="price-badge">{{ $index + 1 }}</div>
                        <div class="product-image">
                            <img src="{{ $sanPham->hinh_anh_url ?: asset('images/default-salad.jpg') }}" 
                                 alt="{{ $sanPham->ten_san_pham }}">
                        </div>
                        <h3 class="product-name">{{ strtoupper($sanPham->ten_san_pham) }}</h3>
                        <div class="product-price">
                            @if($sanPham->bienThes->count() > 0)
                                {{ number_format($sanPham->bienThes->first()->gia, 0, ',', '.') }} VND
                            @else
                                Liên hệ
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="featured-nav">
                <button class="nav-btn" onclick="scrollProducts('left')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="nav-btn" onclick="scrollProducts('right')">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <button class="view-menu-btn" onclick="viewFullMenu()">XEM TOÀN BỘ MENU</button>
        </section>

        <!-- Contact Section -->
        <section class="contact-section">
            <h2 class="contact-title">LIÊN HỆ ĐẶT TIỆC</h2>
            
            <div class="contact-content">
                <div class="contact-item">
                    <img src="{{ asset('images/trangchu/contact-1.png') }}" alt="Đặt tiệc 1">
                    <div class="contact-overlay">
                        <h4>TIỆC SINH NHẬT</h4>
                        <p>Tổ chức tiệc sinh nhật đáng nhớ</p>
                    </div>
                </div>
                <div class="contact-item">
                    <img src="{{ asset('images/trangchu/contact-2.png') }}" alt="Đặt tiệc 2">
                    <div class="contact-overlay">
                        <h4>TIỆC CÔNG TY</h4>
                        <p>Sự kiện doanh nghiệp chuyên nghiệp</p>
                    </div>
                </div>
                <div class="contact-item">
                    <img src="{{ asset('images/trangchu/contact-3.jpg') }}" alt="Đặt tiệc 3">
                    <div class="contact-overlay">
                        <h4>TIỆC GIA ĐÌNH</h4>
                        <p>Không gian ấm cúng cho gia đình</p>
                    </div>
                </div>
            </div>

            <button class="contact-btn" onclick="callNow()">GỌI NGAY 028.6685.9055</button>
        </section>

        <!-- Map Section -->
        <section class="map-section">
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.05727280996!2d106.62625947484402!3d10.806925558630862!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752be27ea41e05%3A0xfa77697a39f13ab0!2zMTQwIEzDqiBUcuG7jW5nIFThuqVuLCBUw6J5IFRo4bqhbmgsIFTDom4gUGjDuiwgSOG7kyBDaMOtIE1pbmggNzAwMDAwLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1759763610641!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentSlide = 0;
const totalSlides = 4;
const slideImages = document.querySelectorAll('.slide-image');
const indicators = document.querySelectorAll('.indicator');

// Circular slider functionality
function updateSlider() {
    slideImages.forEach((img, index) => {
        img.classList.remove('active');
        indicators[index].classList.remove('active');
        
        // Calculate position on circle
        const angle = (index - currentSlide) * (360 / totalSlides);
        const radians = (angle * Math.PI) / 180;
        const radius = 125; // Half of slider width minus image radius
        
        const x = Math.cos(radians) * radius;
        const y = Math.sin(radians) * radius;
        
        img.style.transform = `translate(${x}px, ${y}px)`;
        
        if (index === currentSlide) {
            img.classList.add('active');
            indicators[index].classList.add('active');
        }
    });
}

function nextSlide() {
    currentSlide = (currentSlide + 1) % totalSlides;
    updateSlider();
}

function previousSlide() {
    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
    updateSlider();
}

function goToSlide(index) {
    currentSlide = index;
    updateSlider();
}

// Auto play slider
setInterval(nextSlide, 4000);

// Initialize slider
document.addEventListener('DOMContentLoaded', function() {
    updateSlider();
});

// Scroll to products
function scrollToProducts() {
    document.getElementById('featuredProducts').scrollIntoView({ 
        behavior: 'smooth' 
    });
}

// Scroll products horizontally
function scrollProducts(direction) {
    const container = document.getElementById('productsContainer');
    const scrollAmount = 300;
    
    if (direction === 'left') {
        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    } else {
        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
}

// View full menu
function viewFullMenu() {
    // Redirect to menu page or show modal
    alert('Chức năng xem toàn bộ menu sẽ được phát triển!');
}

// Call now function
function callNow() {
    window.open('tel:02866859055');
}

// Add hover effects to product cards
document.addEventListener('DOMContentLoaded', function() {
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('click', function() {
            const productId = this.getAttribute('data-product');
            // Redirect to product detail or add to cart
            alert('Chức năng xem chi tiết sản phẩm sẽ được phát triển!');
        });
    });
});

// Parallax effect for orange decorations
window.addEventListener('scroll', function() {
    const scrolled = window.pageYOffset;
    const parallax = scrolled * 0.5;
    
    document.querySelectorAll('.orange-decoration').forEach((element, index) => {
        const speed = (index + 1) * 0.3;
        element.style.transform = `translateY(${parallax * speed}px) rotate(${scrolled * 0.1}deg)`;
    });
});

// Animate elements on scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver(function(entries) {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.animation = 'slideIn 0.6s ease-out forwards';
        }
    });
}, observerOptions);

// Observe all sections
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.nutrition-section, .featured-section, .contact-section').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endsection