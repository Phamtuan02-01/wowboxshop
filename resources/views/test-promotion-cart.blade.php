@extends('layouts.app')

@section('title', 'Test Promotion Cart - WOW Box Shop')

@section('styles')
<style>
    .test-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    
    .test-section {
        margin: 20px 0;
        padding: 20px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
    }
    
    .btn-test {
        padding: 10px 20px;
        background: linear-gradient(135deg, #004b00, #006600);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        margin: 5px;
        text-decoration: none;
        display: inline-block;
    }
    
    .status {
        padding: 10px;
        border-radius: 8px;
        margin: 10px 0;
    }
    
    .status.success { background: #d4edda; color: #155724; }
    .status.warning { background: #fff3cd; color: #856404; }
    .status.error { background: #f8d7da; color: #721c24; }
</style>
@endsection

@section('content')
<div class="test-container">
    <h2 style="text-align: center; color: #004b00;">Test Promotion in Cart System</h2>
    
    <div class="test-section">
        <h3>Bước 1: Kiểm tra Login</h3>
        @auth
            <div class="status success">✅ Đã đăng nhập: {{ Auth::user()->ten_dang_nhap }}</div>
        @else
            <div class="status error">❌ Chưa đăng nhập</div>
            <a href="{{ route('dangnhap') }}" class="btn-test">Đăng nhập</a>
        @endauth
    </div>
    
    <div class="test-section">
        <h3>Bước 2: Kiểm tra Products có Khuyến Mãi</h3>
        @php
            $productsWithPromotion = App\Models\SanPham::products()
                ->where('trang_thai', true)
                ->limit(5)
                ->get()
                ->filter(function($product) {
                    return $product->promotion_info['has_promotion'];
                });
        @endphp
        
        @if($productsWithPromotion->count() > 0)
            <div class="status success">✅ Tìm thấy {{ $productsWithPromotion->count() }} sản phẩm có khuyến mãi</div>
            @foreach($productsWithPromotion as $product)
                <div style="border: 1px solid #ddd; padding: 10px; margin: 10px 0; border-radius: 8px;">
                    <strong>{{ $product->ten_san_pham }}</strong><br>
                    Giá gốc: {{ number_format($product->gia, 0, ',', '.') }}đ<br>
                    Giá khuyến mãi: {{ number_format($product->final_price, 0, ',', '.') }}đ<br>
                    Khuyến mãi: {{ $product->promotion_info['promotion_name'] }} 
                    (-{{ $product->discount_percentage }}%)<br>
                    
                    @auth
                        @if($product->bienThes->count() > 0)
                            <button class="btn-test" onclick="addToCart({{ $product->ma_san_pham }}, {{ $product->bienThes->first()->ma_bien_the }})">
                                Thêm vào giỏ
                            </button>
                        @endif
                    @endauth
                </div>
            @endforeach
        @else
            <div class="status warning">⚠️ Không tìm thấy sản phẩm có khuyến mãi đang hoạt động</div>
        @endif
    </div>
    
    <div class="test-section">
        <h3>Bước 3: Kiểm tra Giỏ Hàng</h3>
        @auth
            @php
                $gioHang = Auth::user()->gioHang;
                $tongSoLuong = $gioHang ? $gioHang->chiTietGioHangs->sum('so_luong') : 0;
            @endphp
            
            @if($tongSoLuong > 0)
                <div class="status success">✅ Giỏ hàng có {{ $tongSoLuong }} sản phẩm</div>
                <a href="{{ route('giohang') }}" class="btn-test">Xem Giỏ Hàng</a>
            @else
                <div class="status warning">⚠️ Giỏ hàng trống</div>
            @endif
        @else
            <div class="status error">❌ Cần đăng nhập để xem giỏ hàng</div>
        @endauth
    </div>
    
    <div class="test-section">
        <h3>Bước 4: Kiểm tra Khuyến Mãi Active</h3>
        @php
            $activePromotions = App\Models\KhuyenMai::active()->valid()->count();
            $totalPromotions = App\Models\KhuyenMai::count();
        @endphp
        
        <div class="status {{ $activePromotions > 0 ? 'success' : 'warning' }}">
            {{ $activePromotions > 0 ? '✅' : '⚠️' }} 
            Có {{ $activePromotions }}/{{ $totalPromotions }} khuyến mãi đang hoạt động
        </div>
        
        @if($activePromotions > 0)
            <a href="{{ route('admin.promotions.index') }}" class="btn-test">Xem Khuyến Mãi</a>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function addToCart(maSanPham, maBienThe) {
    fetch('{{ route("giohang.them") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            ma_san_pham: maSanPham,
            ma_bien_the: maBienThe,
            so_luong: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Đã thêm vào giỏ hàng! Vào giỏ hàng để xem khuyến mãi.');
            location.reload();
        } else {
            alert('❌ Lỗi: ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Có lỗi xảy ra: ' + error.message);
    });
}
</script>
@endsection