@extends('layouts.app')

@section('title', 'Về chúng tôi - WowBox Shop')

@section('styles')
<link href="{{ asset('css/veChungToi.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="about-page">
    <!-- Hero Section -->
    <section class="about-hero">
        <div class="about-container">
            <h1 class="page-title">VỀ CHÚNG TÔI</h1>
            <div class="hero-decorations">
                <div class="decoration-circle"></div>
                <div class="decoration-lines"></div>
            </div>
        </div>
    </section>

    <!-- Story Section -->
    <section class="story-section">
        <div class="about-container">
            <div class="story-content">
                <div class="story-text">
                    <h2 class="section-title">VỀ CHÚNG TÔI</h2>
                    <p class="story-paragraph">
                        Chúng tôi là thương hiệu salad hàng đầu tại TP.HCM. 
                        WowBox mang đến cho bạn những trải nghiệm ẩm thực mới lạ.
                    </p>
                    <p class="story-paragraph">
                        Chúng tôi hiểu rằng sự tươi mát và tính an toàn. Thế nên, 
                        sự tự nhiên không chỉ làm một nguyên liệu tươi thơi. TP.HCM 
                        là điểm mà WowBox sử dụng để mang đến cho khách hàng những 
                        nguyên liệu tự nhiên và tốt nhất với hơn 20 loại rau củ khác 
                        nhau chuẩn Âu.
                    </p>
                    <p class="story-paragraph">
                        Quy trình hàng có thể chọn các món salad tươi cập Premium, 
                        thuyền hồng Deluxe, vị sức món salad Hot. Trong nguyên lý 
                        đấy, mang đây, hiểu lý ẩm và tải trong màn làm các màn 
                        salad của mình.
                    </p>
                    <p class="highlight-text">
                        Hàng trăm cách kết hợp khác nhau. Thật nhiều niềm vui!
                    </p>
                </div>
                <div class="story-image">
                    <img src="{{ asset('images/veChungToi/CauChuyenWowBox.jpg') }}" alt="Về chúng tôi" />
                    <div class="image-decoration"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision Section -->
    <section class="vision-section">
        <div class="about-container">
            <div class="vision-content">
                <div class="vision-image">
                    <img src="{{ asset('images/veChungToi/TamNhinSuMenh.jpg') }}" alt="Tầm nhìn & sứ mệnh" />
                    <div class="image-overlay">
                        <div class="overlay-decoration"></div>
                    </div>
                </div>
                <div class="vision-text">
                    <h2 class="section-title green-title">TẦM NHÌN & SỨ MỆNH</h2>
                    <p class="vision-paragraph">
                        WowBox mang đến Sài Gòn một phong cách ẩm thực mới - các món salad tươi ngon và tốt cho sức khỏe. Quý khách hàng có thể chọn các món salad cao cấp Premium, truyền thống Classic và các món salad đặc trưng Signature, hoặc tự chọn các loại nguyên liệu theo sở thích cho phần salad của mình.
                    </p>
                    <p class="vision-paragraph">
                        Với trên 30 loại nguyên liệu cơ bản, 30 loại nguyên liệu cao cấp và trên 15 loại sốt dùng kèm, WowBox đem đến rất nhiều lựa chọn cho bữa ăn tốt cho sức khoẻ của bạn.
                    </p>
                    <p class="vision-paragraph">
                        Duy trì một chế độ ăn tốt cho sức khoẻ với nhiều rau xanh, ít tinh bột kết hợp với một chương trình thể dục hợp lí sẽ giúp bạn luôn khoẻ mạnh, tươi trẻ và tràn đầy sức sống.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="reviews-section">
        <div class="about-container">
            <h2 class="section-title center-title">ĐÁNH GIÁ TỪ KHÁCH HÀNG</h2>
            
            <div class="review-content">
                <div class="review-text">
                    <h3 class="review-subtitle">KHÁCH HÀNG NÓI GÌ VỀ CHÚNG TÔI</h3>
                    <p class="review-paragraph">
                        Duy trì một chế độ ăn tốt cho sức khoẻ với nhiều rau xanh, ít tinh bột kết hợp với một chương trình thể dục hợp lí sẽ giúp bạn luôn khoẻ mạnh, tươi trẻ và tràn đầy sức sống.
                    </p>
                    <p class="review-paragraph">
                        Với trên 30 loại nguyên liệu cơ bản, 30 loại nguyên liệu cao cấp và trên 15 loại sốt dùng kèm, WowBox đem đến rất nhiều lựa chọn cho bữa ăn tốt cho sức khoẻ của bạn.
                    </p>
                    <p class="review-paragraph">
                        WowBox mang đến Sài Gòn một phong cách ẩm thực mới - các món salad tươi ngon và tốt cho sức khỏe. Quý khách hàng có thể chọn các món salad cao cấp Premium, truyền thống Classic và các món salad đặc trưng Signature, hoặc tự chọn các loại nguyên liệu theo sở thích cho phần salad của mình.
                    </p>
                </div>
                <div class="review-image">
                    <img src="{{ asset('images/veChungToi/KhachHangHaiLong.jpg') }}" alt="Khách hàng hài lòng" />
                    <div class="customer-info">
                        <div class="customer-avatar"></div>
                        <div class="rating-stars">
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                            <span class="star">★</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Decorative Elements -->
    <div class="page-decorations">
        <div class="leaf-decoration leaf-1"></div>
        <div class="leaf-decoration leaf-2"></div>
        <div class="circle-decoration circle-1"></div>
        <div class="circle-decoration circle-2"></div>
        <div class="orange-slice orange-1"></div>
        <div class="orange-slice orange-2"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate sections on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, observerOptions);

    // Observe all sections
    document.querySelectorAll('.story-section, .vision-section, .reviews-section').forEach(section => {
        observer.observe(section);
    });

    // Parallax effect for decorations
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const parallax = scrolled * 0.5;
        
        document.querySelectorAll('.page-decorations > *').forEach((element, index) => {
            const speed = (index + 1) * 0.2;
            element.style.transform = `translateY(${parallax * speed}px) rotate(${scrolled * 0.05}deg)`;
        });
    });
});
</script>
@endsection