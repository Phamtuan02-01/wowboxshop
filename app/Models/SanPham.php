<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\KhuyenMai;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    protected $primaryKey = 'ma_san_pham';
    
    // Sử dụng tên cột tùy chỉnh cho timestamps
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';
    
    protected $fillable = [
        'ten_san_pham',
        'ma_danh_muc',
        'loai_san_pham',
        'mo_ta',
        'gia',
        'gia_khuyen_mai',
        'thuong_hieu',
        'xuat_xu',
        'hinh_anh',
        'hinh_anh_phu',
        'trang_thai',
        'la_noi_bat',
        'luot_xem',
        'diem_danh_gia_trung_binh',
        'so_luot_danh_gia'
    ];

    protected $casts = [
        'gia' => 'decimal:0',
        'gia_khuyen_mai' => 'decimal:0',
        'trang_thai' => 'boolean',
        'la_noi_bat' => 'boolean',
        'luot_xem' => 'integer',
        'diem_danh_gia_trung_binh' => 'decimal:1',
        'hinh_anh_phu' => 'array'
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'ma_san_pham';
    }

    // Relationships
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'ma_danh_muc', 'ma_danh_muc');
    }

    public function bienThes()
    {
        return $this->hasMany(BienTheSanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function chiTietGioHangs()
    {
        return $this->hasMany(ChiTietGioHang::class, 'ma_san_pham', 'ma_san_pham');
    }

    // Lấy các khuyến mãi đang áp dụng cho sản phẩm này
    /**
     * Many-to-Many relationship với KhuyenMai qua bảng trung gian
     */
    public function khuyenMaisRelation()
    {
        return $this->belongsToMany(
            KhuyenMai::class,
            'khuyen_mai_san_pham',
            'ma_san_pham',
            'ma_khuyen_mai'
        )->withPivot('gia_tri_giam_cu_the', 'so_lan_ap_dung', 'ngay_them')
         ->withTimestamps();
    }

    /**
     * Query builder để lấy tất cả khuyến mãi áp dụng cho sản phẩm này
     * CHỈ LẤY KHUYẾN MÃI LOẠI PHẦN TRĂM (percent) hoặc PRODUCT_DISCOUNT
     * Hỗ trợ cả cách cũ (JSON) và cách mới (bảng trung gian)
     */
    public function khuyenMais()
    {
        return KhuyenMai::active()
                       ->valid()
                       // Chỉ lấy khuyến mãi % hoặc product_discount cho sản phẩm
                       ->whereIn('loai_khuyen_mai', ['percent', 'product_discount'])
                       ->where(function($query) {
                           // Điều kiện 1: Áp dụng tất cả
                           $query->where('ap_dung_tat_ca', true)
                                 // Điều kiện 2: Có trong bảng trung gian sản phẩm
                                 ->orWhereHas('sanPhams', function($q) {
                                     $q->where('san_pham.ma_san_pham', $this->ma_san_pham);
                                 })
                                 // Điều kiện 3: Có trong bảng trung gian danh mục
                                 ->orWhereHas('danhMucs', function($q) {
                                     $q->where('danh_muc.ma_danh_muc', $this->ma_danh_muc);
                                 })
                                 // Điều kiện 4: Fallback cho JSON field (backward compatibility)
                                 ->orWhereJsonContains('san_pham_ap_dung', $this->ma_san_pham)
                                 ->orWhereJsonContains('danh_muc_ap_dung', $this->ma_danh_muc);
                       });
    }

    // Lấy khuyến mãi tốt nhất cho sản phẩm
    public function getBestPromotionAttribute()
    {
        $promotions = $this->khuyenMais()->get();
        
        $bestPromotion = null;
        $maxDiscount = 0;
        
        foreach ($promotions as $promotion) {
            $discount = $promotion->calculateDiscount($this->gia, [
                ['ma_san_pham' => $this->ma_san_pham, 'gia' => $this->gia, 'so_luong' => 1]
            ]);
            
            if ($discount > $maxDiscount) {
                $maxDiscount = $discount;
                $bestPromotion = $promotion;
            }
        }
        
        return $bestPromotion;
    }

    // Scopes for different product types
    public function scopeProducts($query)
    {
        return $query->where('loai_san_pham', 'product');
    }

    public function scopeIngredients($query)
    {
        return $query->where('loai_san_pham', 'ingredient');
    }

    // Helper methods
    public function isProduct()
    {
        return $this->loai_san_pham === 'product';
    }

    public function isIngredient()
    {
        return $this->loai_san_pham === 'ingredient';
    }

    public function getDiscountPercentageAttribute()
    {
        $promotion = $this->best_promotion;
        if ($promotion) {
            $discount = $promotion->calculateDiscount($this->gia, [
                ['ma_san_pham' => $this->ma_san_pham, 'gia' => $this->gia, 'so_luong' => 1]
            ]);
            
            if ($discount > 0 && $this->gia > 0) {
                return round(($discount / $this->gia) * 100);
            }
        }
        
        // Fallback to old gia_khuyen_mai field
        if ($this->gia_khuyen_mai && $this->gia > 0) {
            return round((($this->gia - $this->gia_khuyen_mai) / $this->gia) * 100);
        }
        
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        $promotion = $this->best_promotion;
        if ($promotion) {
            $discount = $promotion->calculateDiscount($this->gia, [
                ['ma_san_pham' => $this->ma_san_pham, 'gia' => $this->gia, 'so_luong' => 1]
            ]);
            
            if ($discount > 0) {
                return $this->gia - $discount;
            }
        }
        
        // Fallback to old gia_khuyen_mai field
        return $this->gia_khuyen_mai ?: $this->gia;
    }

    // Method mới để lấy thông tin khuyến mãi chi tiết
    public function getPromotionInfoAttribute()
    {
        $promotion = $this->best_promotion;
        if ($promotion) {
            $discount = $promotion->calculateDiscount($this->gia, [
                ['ma_san_pham' => $this->ma_san_pham, 'gia' => $this->gia, 'so_luong' => 1]
            ]);
            
            // Tính giá min/max sau giảm cho tất cả variants
            $variants = $this->bienThes;
            $discountedPrices = [];
            
            foreach ($variants as $variant) {
                $variantDiscount = $promotion->calculateDiscount($variant->gia, [
                    ['ma_san_pham' => $this->ma_san_pham, 'gia' => $variant->gia, 'so_luong' => 1]
                ]);
                $discountedPrices[] = $variant->gia - $variantDiscount;
            }
            
            return [
                'has_promotion' => true,
                'promotion_name' => $promotion->ten_khuyen_mai,
                'promotion_type' => $promotion->loai_khuyen_mai,
                'discount_amount' => $discount,
                'final_price' => $this->gia - $discount,
                'discount_percentage' => $this->gia > 0 ? round(($discount / $this->gia) * 100) : 0,
                'promotion_code' => $promotion->ma_code,
                'promotion_description' => $promotion->mo_ta,
                'promotion_end_date' => $promotion->ngay_ket_thuc,
                'discounted_min_price' => !empty($discountedPrices) ? min($discountedPrices) : $this->gia - $discount,
                'discounted_max_price' => !empty($discountedPrices) ? max($discountedPrices) : $this->gia - $discount,
            ];
        }
        
        // Check old field for backward compatibility
        if ($this->gia_khuyen_mai && $this->gia_khuyen_mai < $this->gia) {
            $discount = $this->gia - $this->gia_khuyen_mai;
            
            // Tính giá min/max sau giảm cho tất cả variants sử dụng gia_khuyen_mai
            $variants = $this->bienThes;
            $discountedPrices = [];
            
            foreach ($variants as $variant) {
                // Áp dụng cùng tỷ lệ giảm giá cho tất cả variants
                $discountRatio = $this->gia_khuyen_mai / $this->gia;
                $discountedPrices[] = $variant->gia * $discountRatio;
            }
            
            return [
                'has_promotion' => true,
                'promotion_name' => 'Khuyến mãi đặc biệt',
                'promotion_type' => 'fixed',
                'discount_amount' => $discount,
                'final_price' => $this->gia_khuyen_mai,
                'discount_percentage' => round(($discount / $this->gia) * 100),
                'promotion_code' => null,
                'promotion_description' => 'Giá khuyến mãi đặc biệt',
                'promotion_end_date' => null,
                'discounted_min_price' => !empty($discountedPrices) ? min($discountedPrices) : $this->gia_khuyen_mai,
                'discounted_max_price' => !empty($discountedPrices) ? max($discountedPrices) : $this->gia_khuyen_mai,
            ];
        }
        
        return [
            'has_promotion' => false,
            'final_price' => $this->gia,
            'discount_percentage' => 0,
            'discounted_min_price' => null,
            'discounted_max_price' => null,
        ];
    }

    public function getStockStatusAttribute()
    {
        $totalStock = $this->getTotalAvailableStock();
        
        if ($totalStock <= 0) {
            return ['text' => 'Hết hàng', 'class' => 'out-of-stock'];
        } elseif ($totalStock <= 10) {
            return ['text' => 'Sắp hết', 'class' => 'low-stock'];
        } else {
            return ['text' => 'Còn hàng', 'class' => 'in-stock'];
        }
    }

    /**
     * Tính tổng số lượng tồn kho từ các biến thể còn hoạt động
     */
    public function getTotalAvailableStock()
    {
        return $this->bienThes()
                   ->where('trang_thai', true)
                   ->where('so_luong_ton', '>', 0)
                   ->sum('so_luong_ton');
    }

    /**
     * Lấy các biến thể có sẵn (còn hàng và hoạt động)
     */
    public function getAvailableVariantsAttribute()
    {
        return $this->bienThes->where('trang_thai', true)->where('so_luong_ton', '>', 0);
    }

    /**
     * Kiểm tra xem sản phẩm có còn hàng không
     */
    public function hasStock()
    {
        return $this->getTotalAvailableStock() > 0;
    }

    /**
     * Lấy khoảng giá của sản phẩm từ các biến thể có sẵn
     */
    public function getPriceRangeAttribute()
    {
        $availableVariants = $this->getAvailableVariantsAttribute();
        
        if ($availableVariants->count() === 0) {
            return ['min' => 0, 'max' => 0, 'display' => 'Hết hàng'];
        }
        
        $minPrice = $availableVariants->min('gia');
        $maxPrice = $availableVariants->max('gia');
        
        // Kiểm tra khuyến mãi
        $promotionInfo = $this->getPromotionInfoAttribute();
        
        if ($promotionInfo && $promotionInfo['has_promotion'] && !is_null($promotionInfo['discounted_min_price'])) {
            // Có khuyến mãi - tính giá sau giảm
            $discountedMinPrice = $promotionInfo['discounted_min_price'];
            $discountedMaxPrice = $promotionInfo['discounted_max_price'];
            
            if ($discountedMinPrice == $discountedMaxPrice) {
                return [
                    'min' => $discountedMinPrice, 
                    'max' => $discountedMaxPrice,
                    'original_min' => $minPrice,
                    'original_max' => $maxPrice,
                    'display' => number_format($discountedMinPrice, 0, ',', '.') . 'đ',
                    'has_promotion' => true
                ];
            } else {
                return [
                    'min' => $discountedMinPrice, 
                    'max' => $discountedMaxPrice,
                    'original_min' => $minPrice,
                    'original_max' => $maxPrice,
                    'display' => number_format($discountedMinPrice, 0, ',', '.') . 'đ - ' . number_format($discountedMaxPrice, 0, ',', '.') . 'đ',
                    'has_promotion' => true
                ];
            }
        } else {
            // Không có khuyến mãi - hiển thị giá gốc
            if ($minPrice == $maxPrice) {
                return [
                    'min' => $minPrice, 
                    'max' => $maxPrice, 
                    'display' => number_format($minPrice, 0, ',', '.') . 'đ',
                    'has_promotion' => false
                ];
            } else {
                return [
                    'min' => $minPrice, 
                    'max' => $maxPrice, 
                    'display' => number_format($minPrice, 0, ',', '.') . 'đ - ' . number_format($maxPrice, 0, ',', '.') . 'đ',
                    'has_promotion' => false
                ];
            }
        }
    }

    /**
     * Tính giá đã giảm cho một variant cụ thể
     */
    public function getDiscountedPriceForVariant($variantPrice)
    {
        $promotion = $this->best_promotion;
        
        if (!$promotion) {
            // Kiểm tra backward compatibility với gia_khuyen_mai
            if ($this->gia_khuyen_mai && $this->gia_khuyen_mai < $this->gia) {
                $discountRatio = $this->gia_khuyen_mai / $this->gia;
                return $variantPrice * $discountRatio;
            }
            return $variantPrice; // Không có khuyến mãi
        }
        
        $discount = $promotion->calculateDiscount($variantPrice, [
            ['ma_san_pham' => $this->ma_san_pham, 'gia' => $variantPrice, 'so_luong' => 1]
        ]);
        
        return $variantPrice - $discount;
    }

    public function getRatingStarsAttribute()
    {
        $rating = $this->diem_danh_gia_trung_binh;
        $fullStars = floor($rating);
        $halfStar = $rating - $fullStars >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;

        return [
            'full' => $fullStars,
            'half' => $halfStar,
            'empty' => $emptyStars
        ];
    }

    public function getImageUrlAttribute()
    {
        if ($this->hinh_anh) {
            return asset('images/products/' . $this->hinh_anh);
        }
        return asset('images/default-product.png');
    }

    public function getAdditionalImagesAttribute()
    {
        if ($this->hinh_anh_phu) {
            $images = json_decode($this->hinh_anh_phu, true);
            if (is_array($images)) {
                return array_map(function($image) {
                    return asset('images/products/' . $image);
                }, $images);
            }
        }
        return [];
    }

    public function incrementViews()
    {
        $this->increment('luot_xem');
    }

    public function updateRating()
    {
        $avgRating = $this->danhGias()->avg('diem_danh_gia');
        $totalRatings = $this->danhGias()->count();
        
        $this->update([
            'diem_danh_gia_trung_binh' => $avgRating ?: 0,
            'so_luot_danh_gia' => $totalRatings
        ]);
    }
}
