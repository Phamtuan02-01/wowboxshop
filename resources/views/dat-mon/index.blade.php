@extends('layouts.app')

@section('title', 'Đặt món - WowBox Shop')

@section('styles')
<link href="{{ asset('css/dat-mon.css') }}" rel="stylesheet">
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
        --bg-color: #fff2ad;
    }

    /* Trang nền chính */
    body {
        background: linear-gradient(135deg, #FFE135, #FFF7A0);
        min-height: 100vh;
    }

    /* Banner Section */
    .menu-banner-section {
        margin-top: 90px;
        margin-bottom: 80px;
    }

    .banner-image-container {
        width: 100%;
        height: 70vh;
        border-radius: 30px;
        overflow: hidden;
        margin-bottom: 70px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .banner-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .banner-text-section {
        text-align: center;
        margin-bottom: 50px;
        padding: 0 10%;
    }

    .banner-main-title {
        font-size: 3.5rem;
        font-weight: 800;
        color: #004b00;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: 2px;
        line-height: 1.2;
    }

    .banner-description {
        font-size: 1.15rem;
        color: #555;
        line-height: 1.8;
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Categories Slider */
    .categories-slider-section {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-bottom: 60px;
        padding: 0 60px;
    }

    .categories-container {
        display: flex;
        gap: 20px;
        overflow-x: auto;
        scroll-behavior: smooth;
        padding: 10px 0;
        flex: 1;
        max-width: 100%;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }

    .categories-container::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }

    .category-item {
        flex: 0 0 auto;
        padding: 15px 35px;
        background: transparent;
        border: 3px dashed #004b00;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
        position: relative;
        will-change: transform;
    }

    .category-item span {
        font-size: 1.3rem;
        font-weight: 300;
        color: #004b00;
        letter-spacing: 1px;
    }

    .category-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 75, 0, 0.2);
    }

    .category-item.active {
        background: #004b00;
        border-style: solid;
    }

    .category-item.active span {
        color: white;
    }

    .category-nav-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: white;
        border: 3px solid #004b00;
        color: #004b00;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        will-change: transform;
        box-shadow: 0 2px 8px rgba(0, 75, 0, 0.15);
    }

    .category-nav-btn:hover {
        background: #004b00;
        color: white;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0, 75, 0, 0.3);
    }
    
    .category-nav-btn:active {
        transform: scale(0.95);
    }
    
    .category-nav-btn.hidden {
        display: none !important;
    }

    /* Filter Section */
    .filter-section {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 75, 0, 0.15);
        border-radius: 15px;
        padding: 30px;
        margin: -40px 0 40px;
        position: relative;
        z-index: 10;
        border: 2px solid var(--primary-color);
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        font-weight: 600;
        color: var(--text-dark);
        margin-bottom: 8px;
        font-size: 0.9rem;
    }

    .filter-group .form-control,
    .filter-group .form-select {
        border: 2px solid var(--border-color);
        border-radius: 10px;
        padding: 12px 15px;
        transition: all 0.3s ease;
    }

    .filter-group .form-control:focus,
    .filter-group .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }

    .filter-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        height: fit-content;
    }

    .filter-btn:hover {
        background: var(--accent-color);
        transform: translateY(-2px);
    }

    .clear-btn {
        background: #6c757d;
        color: white;
        border: none;
        border-radius: 10px;
        padding: 12px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        height: fit-content;
    }

    .clear-btn:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    /* Sort Section */
    .sort-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .sort-info {
        color: var(--text-light);
        font-size: 0.9rem;
    }

    .sort-controls {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .sort-label {
        font-weight: 600;
        color: var(--text-dark);
        margin-right: 10px;
    }

    .sort-select {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 15px;
        min-width: 200px;
    }

    /* Product Grid - 2 columns layout */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
        margin-bottom: 50px;
    }

    .product-card {
        background: transparent;
        border-radius: 25px;
        overflow: hidden;
        box-shadow: none;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        flex-direction: row;
        height: 500px; /* Cố định chiều cao */
        padding: 25px;
        gap: 25px;
    }

    .product-card:hover {
        box-shadow: none;
        transform: translateY(-3px);
    }

    /* Bên trái - 60% */
    .product-left {
        flex: 0 0 60%;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-end;
    }

    /* Div xanh dưới - chiếm 70% chiều cao */
    .product-green-box {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 70%;
        background: #004b00;
        border-radius: 20px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        z-index: 1;
        box-shadow: 0 4px 15px rgba(0, 75, 0, 0.3);
    }

    /* Hình ảnh tròn - 60% chiều cao, nằm chồng lên */
    .product-image-circle {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 85%;
        height: 65%;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-image-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
        border: 6px solid white;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image-circle img {
        transform: scale(1.05);
    }

    /* Giá trong green box */
    .product-price-in-green {
        margin-bottom: 10px;
    }

    .product-price-in-green .current-price {
        font-size: 1.8rem;
        font-weight: 800;
        color: white;
        display: block;
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
    }

    .product-price-in-green .original-price {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.8);
        text-decoration: line-through;
        display: inline-block;
        margin-right: 10px;
    }

    .product-price-in-green .discount-badge {
        background: #e74c3c;
        color: white;
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 700;
        display: inline-block;
    }

    /* Đánh giá trong green box */
    .product-rating-in-green {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .rating-stars {
        display: flex;
        gap: 3px;
    }

    .rating-stars i {
        font-size: 1rem;
        color: #ffd700;
        filter: drop-shadow(1px 1px 2px rgba(0,0,0,0.3));
    }

    .rating-stars i.empty {
        color: rgba(255,255,255,0.4);
    }

    .rating-text {
        font-size: 0.9rem;
        color: white;
        font-weight: 600;
    }

    /* Bên phải - 40% */
    .product-right {
        flex: 0 0 40%;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Tên sản phẩm - font to, gầy, màu xanh */
    .product-name-top {
        margin: 0;
        padding: 0;
    }

    .product-name-top h3 {
        font-size: 2.2rem;
        font-weight: 300;
        color: #004b00;
        margin: 0;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    /* Div chứa danh mục và nguyên liệu */
    .product-details-box {
        background: #fff;
        border-radius: 15px;
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 15px;
        border: 2px solid #004b00;
    }

    .product-category-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding-bottom: 12px;
        border-bottom: 2px solid #004b00;
    }

    .product-category-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .product-category-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #004b00;
    }

    .ingredients-section {
        flex: 1;
    }

    .ingredients-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #004b00;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .ingredients-title i {
        color: #2ecc71;
        font-size: 1rem;
    }

    .ingredients-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px 15px;
        max-width: 100%;
        max-height: 100%; /* Giới hạn chiều cao */
        overflow: hidden;
    }

    /* Mô tả không chia 2 cột */
    .ingredients-section.description-mode .ingredients-list {
        grid-template-columns: 1fr;
        max-height: 150px;
        max-width: 100%;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ingredients-section.description-mode .ingredients-list li {
        white-space: normal;
        word-wrap: break-word;
        overflow: visible;
        max-width: 100%;
    }
    
    /* Hiển thị ... khi vượt quá chiều cao cho mô tả */
    .ingredients-section.description-mode .ingredients-list li:last-child::after {
        content: '';
    }

    .ingredients-list li {
        padding-left: 18px;
        position: relative;
        color: #34495e;
        font-size: 0.9rem;
        line-height: 1.6;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ingredients-list li:before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #004b00;
        font-weight: bold;
        font-size: 1rem;
    }

    /* Badge sale/featured - đặt ở góc card */
    .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #e74c3c;
        color: white;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        z-index: 10;
        box-shadow: 0 3px 10px rgba(231, 76, 60, 0.3);
    }

    .product-badge.featured {
        background: #00a000;
        box-shadow: 0 3px 10px rgba(0, 160, 0, 0.3);
    }

    /* Stock Ribbon */
    .stock-ribbon {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #004b00;
        color: white;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        z-index: 10;
        box-shadow: 0 2px 8px rgba(39, 174, 96, 0.4);
    }

    .product-category {
        color: var(--primary-color);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .product-name {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 10px;
        line-height: 1.4;
        height: 2.8rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-description {
        color: var(--text-light);
        font-size: 0.9rem;
        margin-bottom: 15px;
        line-height: 1.5;
        height: 3rem;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
    }

    .rating-stars {
        display: flex;
        gap: 2px;
    }

    .rating-stars i {
        font-size: 0.8rem;
        color: #ffc107;
    }

    .rating-stars i.empty {
        color: #e0e0e0;
    }

    .rating-text {
        font-size: 0.8rem;
        color: var(--text-light);
    }

    .product-price {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .current-price {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .original-price {
        font-size: 1rem;
        color: var(--text-light);
        text-decoration: line-through;
    }

    .discount-percent {
        background: #e74c3c;
        color: white;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
    }

    .product-actions {
        display: flex;
        gap: 10px;
    }

    /* Size Selection */
    .size-selection {
        margin-bottom: 15px;
    }

    .size-selection label {
        font-size: 0.9rem;
        color: var(--text-dark);
        margin-bottom: 8px;
    }

    .size-select {
        border: 2px solid var(--border-color);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .size-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }

    .size-select option {
        padding: 10px;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        overflow: hidden;
        background: white;
    }

    .quantity-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 8px 12px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .quantity-btn:hover {
        background: var(--accent-color);
    }

    .quantity-input {
        border: none;
        text-align: center;
        width: 50px;
        padding: 8px 5px;
        font-weight: 600;
    }

    .add-to-cart-btn {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .add-to-cart-btn:hover {
        background: var(--accent-color);
        transform: translateY(-2px);
    }

    .add-to-cart-btn:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .btn-detail {
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-detail:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        background: var(--accent-color);
        color: white;
    }

    .stock-status {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 10px;
    }

    .stock-status.in-stock {
        background: #d4edda;
        color: #155724;
    }

    .stock-status.low-stock {
        background: #fff3cd;
        color: #856404;
    }

    .stock-status.out-of-stock {
        background: #f8d7da;
        color: #721c24;
    }

    /* Featured Products Section */
    .featured-section {
        background: transparent;
        padding: 60px 0;
        margin-top: 60px;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 20px;
    }

    .section-subtitle {
        text-align: center;
        color: var(--text-light);
        font-size: 1.1rem;
        margin-bottom: 50px;
    }

    /* Pagination - Giống style Categories Slider */
    .pagination-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 50px;
        box-shadow: none;
        gap: 10px;
    }

    /* Ẩn mobile version và text "Showing x to y" */
    .pagination-wrapper nav > div:first-child {
        display: none !important;
    }
    
    .pagination-wrapper nav > div > div:first-child {
        display: none !important;
    }

    /* Container chứa các nút pagination */
    .pagination-wrapper nav > div > div:last-child,
    .pagination-wrapper nav span {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 10px !important;
    }

    /* Style cho tất cả nút pagination - Giống category-nav-btn */
    .pagination-wrapper nav a,
    .pagination-wrapper nav span span {
        width: 50px !important;
        height: 50px !important;
        border-radius: 50% !important;
        background: white !important;
        border: 3px solid #004b00 !important;
        color: #004b00 !important;
        font-size: 1.2rem !important;
        font-weight: 600 !important;
        cursor: pointer !important;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: 0 2px 8px rgba(0, 75, 0, 0.15) !important;
        text-decoration: none !important;
        will-change: transform !important;
    }

    /* Hover */
    .pagination-wrapper nav a:hover {
        background: #004b00 !important;
        color: white !important;
        transform: scale(1.1) !important;
        box-shadow: 0 4px 12px rgba(0, 75, 0, 0.3) !important;
    }

    /* Active/Current page */
    .pagination-wrapper nav span[aria-current="page"] span {
        background: #004b00 !important;
        color: white !important;
    }

    /* Disabled */
    .pagination-wrapper nav span[aria-disabled="true"] span {
        opacity: 0.4 !important;
        cursor: not-allowed !important;
        background: #f0f0f0 !important;
        border-color: #ccc !important;
        color: #999 !important;
        box-shadow: none !important;
    }

    .pagination-wrapper nav span[aria-disabled="true"] span:hover {
        transform: none !important;
        background: #f0f0f0 !important;
    }

    /* SVG icons */
    .pagination-wrapper nav svg {
        width: 20px !important;
        height: 20px !important;
    }

    /* Loading Animation */
    .loading {
        text-align: center;
        padding: 50px;
    }

    .loading i {
        font-size: 3rem;
        color: var(--primary-color);
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: var(--text-light);
        margin-bottom: 20px;
    }

    .empty-state h3 {
        color: var(--text-dark);
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--text-light);
        margin-bottom: 30px;
    }

    .empty-state .btn {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 25px;
        padding: 12px 30px;
        font-weight: 600;
        text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .banner-image-container {
            height: 40vh;
            border-radius: 20px;
        }

        .banner-main-title {
            font-size: 2rem;
        }

        .banner-description {
            font-size: 1rem;
        }

        .banner-text-section {
            padding: 0 5%;
        }

        .categories-slider-section {
            padding: 0 20px;
        }

        .category-item {
            padding: 12px 25px;
        }

        .category-item span {
            font-size: 1.1rem;
        }

        .filter-form {
            flex-direction: column;
        }

        .filter-group {
            min-width: 100%;
        }

        .sort-section {
            flex-direction: column;
            align-items: stretch;
        }

        .sort-controls {
            justify-content: space-between;
        }

        .products-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .product-card {
            flex-direction: column;
            min-height: auto;
        }

        .product-left {
            flex: 0 0 250px;
        }

        .product-info {
            padding: 20px;
        }

        .ingredients-list li {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .hero-section {
            padding: 60px 0 40px;
        }

        .filter-section {
            margin: -30px 15px 30px;
            padding: 20px;
        }

        .products-grid {
            grid-template-columns: 1fr;
        }

        .product-name-overlay h3 {
            font-size: 1.1rem;
        }

        .product-price-section .current-price {
            font-size: 1.2rem;
        }

        .ingredients-list li {
            font-size: 0.8rem;
        }
    }

    /* Custom Notification System */
    .custom-notification-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        max-width: 400px;
        display: block;
        visibility: visible;
    }

    .custom-notification {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        margin-bottom: 15px;
        overflow: hidden;
        border: none;
        min-width: 320px;
        animation: customSlideInRight 0.4s ease-out;
        position: relative;
    }

    .custom-notification.fade-out {
        animation: customSlideOutRight 0.4s ease-in forwards;
    }

    .custom-notification .notification-header {
        display: flex;
        align-items: center;
        padding: 1rem 1.2rem 0.8rem;
        border-bottom: none;
        background: transparent;
    }

    .custom-notification .notification-body {
        padding: 0 1.2rem 1rem;
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .custom-notification .notification-icon {
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

    .custom-notification .notification-content {
        flex: 1;
    }

    .custom-notification .notification-title {
        font-weight: 600;
        margin: 0;
        font-size: 0.95rem;
    }

    .custom-notification .notification-close {
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
        color: #666;
    }

    .custom-notification .notification-close:hover {
        opacity: 1;
    }

    /* Success Notification */
    .custom-notification.notification-success {
        border-left: 4px solid #28a745;
    }

    .custom-notification.notification-success .notification-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .custom-notification.notification-success .notification-title {
        color: #155724;
    }

    .custom-notification.notification-success .notification-body {
        color: #155724;
    }

    /* Error Notification */
    .custom-notification.notification-error {
        border-left: 4px solid #dc3545;
    }

    .custom-notification.notification-error .notification-icon {
        background: linear-gradient(135deg, #dc3545, #e74c3c);
        color: white;
    }

    .custom-notification.notification-error .notification-title {
        color: #721c24;
    }

    .custom-notification.notification-error .notification-body {
        color: #721c24;
    }

    /* Warning Notification */
    .custom-notification.notification-warning {
        border-left: 4px solid #ffc107;
    }

    .custom-notification.notification-warning .notification-icon {
        background: linear-gradient(135deg, #ffc107, #f39c12);
        color: white;
    }

    .custom-notification.notification-warning .notification-title {
        color: #856404;
    }

    .custom-notification.notification-warning .notification-body {
        color: #856404;
    }

    /* Info Notification */
    .custom-notification.notification-info {
        border-left: 4px solid #17a2b8;
    }

    .custom-notification.notification-info .notification-icon {
        background: linear-gradient(135deg, #17a2b8, #3498db);
        color: white;
    }

    .custom-notification.notification-info .notification-title {
        color: #0c5460;
    }

    .custom-notification.notification-info .notification-body {
        color: #0c5460;
    }

    /* Progress bar */
    .custom-notification .notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: rgba(0, 0, 0, 0.2);
        animation: customProgressBar 3s linear forwards;
    }

    .custom-notification.notification-success .notification-progress {
        background: #28a745;
    }

    .custom-notification.notification-error .notification-progress {
        background: #dc3545;
    }

    .custom-notification.notification-warning .notification-progress {
        background: #ffc107;
    }

    .custom-notification.notification-info .notification-progress {
        background: #17a2b8;
    }

    /* Custom Animations */
    @keyframes customSlideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes customSlideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }

    @keyframes customProgressBar {
        from {
            width: 100%;
        }
        to {
            width: 0%;
        }
    }

    /* Responsive cho Custom Notification */
    @media (max-width: 768px) {
        .custom-notification-container {
            bottom: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }

        .custom-notification {
            min-width: auto;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container">
    <!-- Banner Section -->
    <section class="menu-banner-section">
        <div class="banner-image-container">
            <img src="{{ asset('images/trangchu/nutrition-salad.jpg') }}" alt="Thực đơn WowBox" class="banner-image">
        </div>
        
        <div class="banner-text-section">
            <h1 class="banner-main-title">Thực  đơn sẵn sàng dành cho bạn</h1>
            <p class="banner-description">
                Từ những món salad truyền thống đến fusion. Từ sinh tố xanh đơn giản đến vi diệu. Và những món ăn kèm để share với bạn bè. Bếp trưởng của WowBox đã nghiên cứu và chọn ra những công thức kết hợp nguyên liệu salad, nước sốt dressing, nước uống bổ dưỡng, ngon lành để phục vụ bạn.
            </p>
        </div>

        <!-- Categories Slider -->
        <div class="categories-slider-section">
            <button class="category-nav-btn prev-btn hidden" id="prevCategoryBtn">
                <i class="fas fa-chevron-left"></i>
            </button>
            
            <div class="categories-container" id="categoriesContainer">
                <div class="category-item {{ request('danh_muc') == '' ? 'active' : '' }}" data-category="">
                    <span>Tất Cả</span>
                </div>
                @foreach($danhMucs as $danhMuc)
                    <div class="category-item {{ request('danh_muc') == $danhMuc->ma_danh_muc ? 'active' : '' }}" 
                         data-category="{{ $danhMuc->ma_danh_muc }}">
                        <span>{{ mb_convert_case($danhMuc->ten_danh_muc, MB_CASE_TITLE, 'UTF-8') }}</span>
                    </div>
                @endforeach
            </div>
            
            <button class="category-nav-btn next-btn hidden" id="nextCategoryBtn">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </section>

    <!-- Filter Section (Hidden by default, can be toggled) -->
    <section class="filter-section" style="display: none;">
        <form class="filter-form" method="GET" action="{{ route('dat-mon.index') }}">
            <div class="filter-group">
                <label for="search">Tìm kiếm món ăn</label>
                <input type="text" class="form-control" id="search" name="search" 
                       placeholder="Nhập tên món ăn..." value="{{ request('search') }}">
            </div>
            
            <div class="filter-group">
                <label for="danh_muc">Danh mục</label>
                <select class="form-select" id="danh_muc" name="danh_muc">
                    <option value="">Tất cả danh mục</option>
                    @foreach($danhMucs as $danhMuc)
                        <option value="{{ $danhMuc->ma_danh_muc }}" 
                                {{ request('danh_muc') == $danhMuc->ma_danh_muc ? 'selected' : '' }}>
                            {{ $danhMuc->ten_danh_muc }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label for="gia_min">Giá từ</label>
                <input type="number" class="form-control" id="gia_min" name="gia_min" 
                       placeholder="0" value="{{ request('gia_min') }}" min="0">
            </div>
            
            <div class="filter-group">
                <label for="gia_max">Giá đến</label>
                <input type="number" class="form-control" id="gia_max" name="gia_max" 
                       placeholder="1000000" value="{{ request('gia_max') }}" min="0">
            </div>
            
            <div class="filter-group">
                <button type="submit" class="filter-btn">
                    <i class="fas fa-search me-2"></i>Tìm kiếm
                </button>
            </div>
            
            <div class="filter-group">
                <a href="{{ route('dat-mon.index') }}" class="clear-btn">
                    <i class="fas fa-times me-2"></i>Xóa bộ lọc
                </a>
            </div>
        </form>
    </section>

    <!-- Sort and Results Info Section -->
    <section class="sort-section">
        <div class="sort-info">
            <span>Hiển thị {{ $sanPhams->count() }} / {{ $sanPhams->total() }} sản phẩm</span>
        </div>
        <div class="sort-controls">
            <span class="sort-label">Sắp xếp theo:</span>
            <select class="form-select sort-select" id="sort-select" data-current-sort="{{ request('sort', '') }}">
                <option value="" {{ !request('sort') || request('sort') == '' ? 'selected' : '' }}>Mặc định</option>
                <option value="moi_nhat" {{ request('sort') == 'moi_nhat' ? 'selected' : '' }}>Mới nhất</option>
                <option value="gia_thap" {{ request('sort') == 'gia_thap' ? 'selected' : '' }}>Giá thấp đến cao</option>
                <option value="gia_cao" {{ request('sort') == 'gia_cao' ? 'selected' : '' }}>Giá cao đến thấp</option>
                <option value="ten_az" {{ request('sort') == 'ten_az' ? 'selected' : '' }}>Tên A-Z</option>
                <option value="ten_za" {{ request('sort') == 'ten_za' ? 'selected' : '' }}>Tên Z-A</option>
                <option value="noi_bat" {{ request('sort') == 'noi_bat' ? 'selected' : '' }}>Nổi bật</option>
                <option value="danh_gia" {{ request('sort') == 'danh_gia' ? 'selected' : '' }}>Đánh giá cao</option>
                <option value="ban_chay" {{ request('sort') == 'ban_chay' ? 'selected' : '' }}>Bán chạy</option>
            </select>
        </div>
    </section>

    <!-- Products Grid -->
    @if($sanPhams->count() > 0 || $sanPhamNoiBat->count() > 0)
        <section class="products-grid">
            {{-- Chỉ hiển thị sản phẩm nổi bật khi KHÔNG có tùy chọn sắp xếp (mặc định) --}}
            @if($sanPhamNoiBat->count() > 0 && (!request('sort') || request('sort') == ''))
                @foreach($sanPhamNoiBat as $sanPham)
                <div class="product-card" data-product-id="{{ $sanPham->ma_san_pham }}">
                    <!-- Badge sale/featured -->
                    @if($sanPham->promotion_info['has_promotion'] ?? false)
                        <div class="product-badge sale">
                            -{{ $sanPham->promotion_info['discount_percentage'] ?? 0 }}%
                        </div>
                    @elseif($sanPham->la_noi_bat)
                        <div class="product-badge featured">
                            Nổi bật
                        </div>
                    @endif
                    
                    @if($sanPham->hasStock())
                        <div class="stock-ribbon">Còn hàng</div>
                    @endif
                    
                    <!-- Bên trái - 40% -->
                    <div class="product-left">
                        <!-- Hình ảnh tròn - 60% chiều cao từ trên -->
                        <div class="product-image-circle">
                            <img src="{{ $sanPham->image_url }}" alt="{{ $sanPham->ten_san_pham }}" loading="lazy">
                        </div>
                        
                        <!-- Div xanh - 70% chiều cao từ dưới -->
                        <div class="product-green-box">
                            <!-- Giá -->
                            <div class="product-price-in-green">
                                @if($sanPham->promotion_info['has_promotion'] ?? false)
                                    <span class="current-price">
                                        {{ number_format($sanPham->promotion_info['discounted_min_price'], 0, ',', '.') }}đ
                                    </span>
                                    <div>
                                        <span class="original-price">
                                            {{ number_format($sanPham->price_range['original_min'], 0, ',', '.') }}đ
                                        </span>
                                        <span class="discount-badge">
                                            -{{ $sanPham->promotion_info['discount_percentage'] ?? 0 }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="current-price">
                                        {{ number_format($sanPham->price_range['min'], 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Đánh giá -->
                            @if($sanPham->diem_danh_gia_trung_binh > 0)
                                <div class="product-rating-in-green">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($sanPham->diem_danh_gia_trung_binh))
                                                <i class="fas fa-star"></i>
                                            @elseif($i == ceil($sanPham->diem_danh_gia_trung_binh) && $sanPham->diem_danh_gia_trung_binh - floor($sanPham->diem_danh_gia_trung_binh) >= 0.5)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="fas fa-star empty"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-text">({{ $sanPham->so_luot_danh_gia }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Bên phải - 60% -->
                    <div class="product-right">
                        <!-- Tên sản phẩm - font to, gầy, màu xanh -->
                        <div class="product-name-top">
                            <h3>{{ mb_convert_case($sanPham->ten_san_pham, MB_CASE_TITLE, 'UTF-8') }}</h3>
                        </div>
                        
                        <!-- Div chứa danh mục và nguyên liệu -->
                        <div class="product-details-box">
                            <!-- Danh mục -->
                            <div class="product-category-row">
                                <span class="product-category-label">Danh mục:</span>
                                <span class="product-category-value">{{ $sanPham->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}</span>
                            </div>
                            
                            <!-- Nguyên liệu hoặc Mô tả -->
                            <div class="ingredients-section {{ $sanPham->danhMuc && stripos($sanPham->danhMuc->ten_danh_muc, 'salad') !== false ? '' : 'description-mode' }}">
                                <div class="ingredients-title">
                                    <i class="fas fa-list-ul"></i>
                                    {{ $sanPham->danhMuc && stripos($sanPham->danhMuc->ten_danh_muc, 'salad') !== false ? 'Nguyên liệu:' : 'Mô tả:' }}
                                </div>
                                <ul class="ingredients-list">
                                    @if($sanPham->mo_ta)
                                        @foreach(explode("\n", $sanPham->mo_ta) as $line)
                                            @if(trim($line))
                                                <li>{{ trim($line) }}</li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li>Cải xoăn</li>
                                        <li>Dưa hấu nướng</li>
                                        <li>Hành tây</li>
                                        <li>Ớt đen</li>
                                        <li>Hành nhăn</li>
                                        <li>Phô mai Feta</li>
                                        <li>Gà ướp quế</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            
            {{-- Hiển thị các sản phẩm từ danh sách filtered (loại bỏ sản phẩm nổi bật đã hiển thị nếu đang ở chế độ mặc định) --}}
            @php
                $displayedProductIds = (!request('sort') || request('sort') == '') ? $sanPhamNoiBat->pluck('ma_san_pham')->toArray() : [];
            @endphp
            
            @foreach($sanPhams as $sanPham)
                @if(!in_array($sanPham->ma_san_pham, $displayedProductIds))
                <div class="product-card" data-product-id="{{ $sanPham->ma_san_pham }}">
                    @if($sanPham->promotion_info['has_promotion'] ?? false)
                        <div class="product-badge sale">
                            -{{ $sanPham->promotion_info['discount_percentage'] ?? 0 }}%
                        </div>
                    @elseif($sanPham->la_noi_bat)
                        <div class="product-badge featured">
                            Nổi bật
                        </div>
                    @endif
                    
                    @if($sanPham->hasStock())
                        <div class="stock-ribbon">Còn hàng</div>
                    @endif
                    
                    <div class="product-left">
                        <div class="product-image-circle">
                            <img src="{{ $sanPham->image_url }}" alt="{{ $sanPham->ten_san_pham }}" loading="lazy">
                        </div>
                        
                        <div class="product-green-box">
                            <div class="product-price-in-green">
                                @if($sanPham->promotion_info['has_promotion'] ?? false)
                                    <span class="current-price">
                                        {{ number_format($sanPham->promotion_info['discounted_min_price'], 0, ',', '.') }}đ
                                    </span>
                                    <div>
                                        <span class="original-price">
                                            {{ number_format($sanPham->price_range['original_min'], 0, ',', '.') }}đ
                                        </span>
                                        <span class="discount-badge">
                                            -{{ $sanPham->promotion_info['discount_percentage'] ?? 0 }}%
                                        </span>
                                    </div>
                                @else
                                    <span class="current-price">
                                        {{ number_format($sanPham->price_range['min'], 0, ',', '.') }}đ
                                    </span>
                                @endif
                            </div>
                            
                            @if($sanPham->diem_danh_gia_trung_binh > 0)
                                <div class="product-rating-in-green">
                                    <div class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($sanPham->diem_danh_gia_trung_binh))
                                                <i class="fas fa-star"></i>
                                            @elseif($i == ceil($sanPham->diem_danh_gia_trung_binh) && $sanPham->diem_danh_gia_trung_binh - floor($sanPham->diem_danh_gia_trung_binh) >= 0.5)
                                                <i class="fas fa-star-half-alt"></i>
                                            @else
                                                <i class="fas fa-star empty"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span class="rating-text">({{ $sanPham->so_luot_danh_gia }})</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="product-right">
                        <div class="product-name-top">
                            <h3>{{ mb_convert_case($sanPham->ten_san_pham, MB_CASE_TITLE, 'UTF-8') }}</h3>
                        </div>
                        
                        <div class="product-details-box">
                            <div class="product-category-row">
                                <span class="product-category-label">Danh mục:</span>
                                <span class="product-category-value">{{ $sanPham->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}</span>
                            </div>
                            
                            <div class="ingredients-section {{ $sanPham->danhMuc && stripos($sanPham->danhMuc->ten_danh_muc, 'salad') !== false ? '' : 'description-mode' }}">
                                <div class="ingredients-title">
                                    <i class="fas fa-list-ul"></i>
                                    {{ $sanPham->danhMuc && stripos($sanPham->danhMuc->ten_danh_muc, 'salad') !== false ? 'Nguyên liệu:' : 'Mô tả:' }}
                                </div>
                                <ul class="ingredients-list">
                                    @if($sanPham->mo_ta)
                                        @foreach(explode("\n", $sanPham->mo_ta) as $line)
                                            @if(trim($line))
                                                <li>{{ trim($line) }}</li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li>Cải xoăn</li>
                                        <li>Dưa hấu nướng</li>
                                        <li>Hành tây</li>
                                        <li>Ớt đen</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @endforeach
        </section>

        <!-- Pagination -->
        @if($sanPhams->hasPages())
            <div class="pagination-wrapper">
                {{ $sanPhams->links() }}
            </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <i class="fas fa-search"></i>
            <h3>Không tìm thấy sản phẩm nào</h3>
            <p>Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm khác</p>
            <a href="{{ route('dat-mon.index') }}" class="btn">
                <i class="fas fa-refresh me-2"></i>Xem tất cả sản phẩm
            </a>
        </div>
    @endif
</div>

<!-- Featured Products Section -->
@if($sanPhamNoiBat->count() > 0)
    <section class="featured-section">
        <div class="container">
            <h2 class="section-title">Sản phẩm nổi bật</h2>
            <p class="section-subtitle">Những món ăn được yêu thích nhất tại WowBox Shop</p>
            
            <div class="products-grid">
                @foreach($sanPhamNoiBat as $sanPham)
                    <div class="product-card" data-product-id="{{ $sanPham->ma_san_pham }}">
                        <!-- Badge nổi bật -->
                        <div class="product-badge featured">Nổi bật</div>
                        
                        @if($sanPham->hasStock())
                            <div class="stock-ribbon">Còn hàng</div>
                        @endif
                        
                        <!-- Bên trái - 40% -->
                        <div class="product-left">
                            <!-- Hình ảnh tròn - 60% chiều cao từ trên -->
                            <div class="product-image-circle">
                                <img src="{{ $sanPham->image_url }}" alt="{{ $sanPham->ten_san_pham }}" loading="lazy">
                            </div>
                            
                            <!-- Div xanh - 70% chiều cao từ dưới -->
                            <div class="product-green-box">
                                <!-- Giá -->
                                <div class="product-price-in-green">
                                    @if($sanPham->promotion_info['has_promotion'] ?? false)
                                        <span class="current-price">
                                            {{ number_format($sanPham->promotion_info['discounted_min_price'], 0, ',', '.') }}đ
                                        </span>
                                        <div>
                                            <span class="original-price">
                                                {{ number_format($sanPham->price_range['original_min'], 0, ',', '.') }}đ
                                            </span>
                                            <span class="discount-badge">
                                                -{{ $sanPham->promotion_info['discount_percentage'] ?? 0 }}%
                                            </span>
                                        </div>
                                    @else
                                        <span class="current-price">
                                            {{ number_format($sanPham->price_range['min'], 0, ',', '.') }}đ
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Đánh giá -->
                                @if($sanPham->diem_danh_gia_trung_binh > 0)
                                    <div class="product-rating-in-green">
                                        <div class="rating-stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($sanPham->diem_danh_gia_trung_binh))
                                                    <i class="fas fa-star"></i>
                                                @elseif($i == ceil($sanPham->diem_danh_gia_trung_binh) && $sanPham->diem_danh_gia_trung_binh - floor($sanPham->diem_danh_gia_trung_binh) >= 0.5)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="fas fa-star empty"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="rating-text">({{ $sanPham->so_luot_danh_gia }})</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Bên phải - 60% -->
                        <div class="product-right">
                            <!-- Tên sản phẩm - font to, gầy, màu xanh -->
                            <div class="product-name-top">
                                <h3>{{ mb_convert_case($sanPham->ten_san_pham, MB_CASE_TITLE, 'UTF-8') }}</h3>
                            </div>
                            
                            <!-- Div chứa danh mục và nguyên liệu -->
                            <div class="product-details-box">
                                <!-- Danh mục -->
                                <div class="product-category-row">
                                    <span class="product-category-label">Danh mục:</span>
                                    <span class="product-category-value">{{ $sanPham->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}</span>
                                </div>
                                
                                <!-- Nguyên liệu hoặc Mô tả -->
                                <div class="ingredients-section {{ $sanPham->danhMuc && stripos($sanPham->danhMuc->ten_danh_muc, 'salad') !== false ? '' : 'description-mode' }}">
                                    <div class="ingredients-title">
                                        <i class="fas fa-list-ul"></i>
                                        {{ $sanPham->danhMuc && stripos($sanPham->danhMuc->ten_danh_muc, 'salad') !== false ? 'Nguyên liệu:' : 'Mô tả:' }}
                                    </div>
                                    <ul class="ingredients-list">
                                        @if($sanPham->mo_ta)
                                            @foreach(explode("\n", $sanPham->mo_ta) as $line)
                                                @if(trim($line))
                                                    <li>{{ trim($line) }}</li>
                                                @endif
                                            @endforeach
                                        @else
                                            <li>Cải xoăn</li>
                                            <li>Dưa hấu nướng</li>
                                            <li>Hành tây</li>
                                            <li>Ớt đen</li>
                                            <li>Hành nhăn</li>
                                            <li>Phô mai Feta</li>
                                            <li>Gà ướp quế</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Custom Notification Container -->
<div class="custom-notification-container" id="customNotificationContainer"></div>
@endsection

@section('scripts')
<script src="{{ asset('js/dat-mon.js') }}"></script>
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
    // Add custom easing for smooth animation
    $.easing.easeInOutCubic = function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
        return c/2*((t-=2)*t*t + 2) + b;
    };
    
    // Categories Slider
    const categoriesContainer = $('#categoriesContainer');
    const prevBtn = $('#prevCategoryBtn');
    const nextBtn = $('#nextCategoryBtn');
    
    function checkScrollButtons() {
        const container = categoriesContainer[0];
        const isOverflowing = container.scrollWidth > container.clientWidth;
        
        if (isOverflowing) {
            const scrollLeft = container.scrollLeft;
            const maxScroll = container.scrollWidth - container.clientWidth;
            
            // Show/hide buttons based on scroll position
            if (scrollLeft <= 0) {
                prevBtn.addClass('hidden');
            } else {
                prevBtn.removeClass('hidden');
            }
            
            if (scrollLeft >= maxScroll - 1) {
                nextBtn.addClass('hidden');
            } else {
                nextBtn.removeClass('hidden');
            }
        } else {
            prevBtn.addClass('hidden');
            nextBtn.addClass('hidden');
        }
    }
    
    // Check on load and resize
    checkScrollButtons();
    $(window).on('resize', checkScrollButtons);
    categoriesContainer.on('scroll', checkScrollButtons);
    
    // Navigation buttons with smooth scroll
    prevBtn.on('click', function() {
        const container = categoriesContainer[0];
        const scrollAmount = 350;
        
        categoriesContainer.animate({
            scrollLeft: container.scrollLeft - scrollAmount
        }, 600, 'easeInOutCubic', checkScrollButtons);
    });
    
    nextBtn.on('click', function() {
        const container = categoriesContainer[0];
        const scrollAmount = 350;
        
        categoriesContainer.animate({
            scrollLeft: container.scrollLeft + scrollAmount
        }, 600, 'easeInOutCubic', checkScrollButtons);
    });
    
    // Category click handler
    $('.category-item').on('click', function() {
        const categoryId = $(this).data('category');
        
        // Update active state
        $('.category-item').removeClass('active');
        $(this).addClass('active');
        
        // Redirect with category filter
        const url = new URL(window.location.href);
        if (categoryId) {
            url.searchParams.set('danh_muc', categoryId);
        } else {
            url.searchParams.delete('danh_muc');
        }
        window.location.href = url.toString();
    });
    // Cập nhật CSRF token cho Ajax
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Custom Notification System - Riêng cho trang này
    window.closeCustomNotification = function(button) {
        const notification = button.closest('.custom-notification');
        if (notification) {
            notification.classList.add('fade-out');
            setTimeout(function() {
                notification.remove();
            }, 400);
        }
    };

    window.showCustomNotification = function(type, title, message) {
        const container = document.getElementById('customNotificationContainer');
        if (!container) {
            console.error('Custom notification container not found!');
            return;
        }

        const icons = {
            'success': 'fas fa-check',
            'error': 'fas fa-times',
            'warning': 'fas fa-exclamation',
            'info': 'fas fa-info'
        };

        const defaultTitles = {
            'success': 'Thành công!',
            'error': 'Lỗi!',
            'warning': 'Cảnh báo!',
            'info': 'Thông tin!'
        };

        const notificationDiv = document.createElement('div');
        notificationDiv.className = `custom-notification notification-${type}`;
        notificationDiv.setAttribute('role', 'alert');
        
        notificationDiv.innerHTML = `
            <div class="notification-header">
                <div class="notification-icon">
                    <i class="${icons[type]}"></i>
                </div>
                <div class="notification-content">
                    <div class="notification-title">${title || defaultTitles[type]}</div>
                </div>
                <button type="button" class="notification-close" onclick="closeCustomNotification(this)" aria-label="Close">
                    ×
                </button>
            </div>
            <div class="notification-body">
                ${message}
            </div>
            <div class="notification-progress"></div>
        `;
        
        container.appendChild(notificationDiv);
        
        // Tự động ẩn sau 3 giây
        setTimeout(function() {
            if (notificationDiv && notificationDiv.querySelector('.notification-close')) {
                closeCustomNotification(notificationDiv.querySelector('.notification-close'));
            }
        }, 3000);
    };

    // Test notification khi trang load (có thể bỏ comment để test)
    setTimeout(function() {
        console.log('Testing custom notification...');
        console.log('Container exists:', !!document.getElementById('customNotificationContainer'));
        
        // Uncomment để test notification
        // showCustomNotification('success', 'Test', 'Notification riêng đang hoạt động!');
    }, 1000);

    // Bỏ các xử lý thêm vào giỏ hàng vì giờ chỉ có nút "Xem chi tiết"

    // Xử lý thay đổi sắp xếp
    $('#sort-select').on('change', function() {
        const sortValue = $(this).val();
        console.log('Sort value selected:', sortValue);
        
        const currentUrl = new URL(window.location.href);
        console.log('Current URL:', currentUrl.toString());
        
        if (sortValue && sortValue !== '') {
            currentUrl.searchParams.set('sort', sortValue);
        } else {
            currentUrl.searchParams.delete('sort');
        }
        
        console.log('New URL:', currentUrl.toString());
        
        // Thêm loading indicator
        $(this).prop('disabled', true);
        $('body').css('cursor', 'wait');
        
        // Reload trang với URL mới
        window.location.href = currentUrl.toString();
    });

    // Lazy loading cho hình ảnh
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
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

    // Smooth scroll cho anchor links
    $('a[href^="#"]').on('click', function(e) {
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        if (target.length) {
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 500);
        }
    });

    // Hiển thị loading khi submit form
    $('.filter-form').on('submit', function() {
        const submitBtn = $(this).find('.filter-btn');
        const originalText = submitBtn.html();
        
        submitBtn.prop('disabled', true);
        submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Đang tìm...');
        
        // Khôi phục button sau 5 giây nếu có lỗi
        setTimeout(() => {
            submitBtn.prop('disabled', false);
            submitBtn.html(originalText);
        }, 5000);
    });
});

// Function hiển thị thông báo thành công - Sử dụng notification riêng
function showSuccess(message) {
    console.log('showSuccess called with message:', message);
    showCustomNotification('success', 'Thành công!', message);
}

// Function hiển thị thông báo lỗi - Sử dụng notification riêng
function showError(message) {
    console.log('showError called with message:', message);
    showCustomNotification('error', 'Lỗi!', message);
}

// Thêm các helper functions khác
function showWarning(message) {
    showCustomNotification('warning', 'Cảnh báo!', message);
}

function showInfo(message) {
    showCustomNotification('info', 'Thông tin!', message);
}

// Xử lý click vào product card để vào chi tiết sản phẩm
$(document).on('click', '.product-card', function(e) {
    const productId = $(this).data('product-id');
    if (productId) {
        window.location.href = '{{ route("dat-mon.chitiet", ":id") }}'.replace(':id', productId);
    }
});

// Thêm cursor pointer cho product card
$('.product-card').css('cursor', 'pointer');
</script>
@endsection