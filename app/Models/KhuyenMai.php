<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'khuyen_mai';
    protected $primaryKey = 'ma_khuyen_mai';
    
    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'ma_khuyen_mai';
    }
    
    protected $fillable = [
        'ten_khuyen_mai',
        'mo_ta',
        'ma_code',
        'loai_khuyen_mai',
        'gia_tri',
        'gia_tri_toi_da',
        'don_hang_toi_thieu',
        'so_luong_su_dung',
        'gioi_han_su_dung',
        'gioi_han_moi_khach',
        'ngay_bat_dau',
        'ngay_ket_thuc',
        'trang_thai',
        'san_pham_ap_dung',
        'danh_muc_ap_dung',
        'ap_dung_tat_ca',
        'hinh_anh'
    ];

    protected $casts = [
        'ngay_bat_dau' => 'datetime',
        'ngay_ket_thuc' => 'datetime',
        'trang_thai' => 'boolean',
        'ap_dung_tat_ca' => 'boolean',
        'san_pham_ap_dung' => 'array',
        'danh_muc_ap_dung' => 'array',
    ];

    // Relationships
    public function lichSuKhuyenMai()
    {
        return $this->hasMany(LichSuKhuyenMai::class, 'ma_khuyen_mai', 'ma_khuyen_mai');
    }

    public function sanPhamApDung()
    {
        if ($this->ap_dung_tat_ca) {
            return SanPham::where('trang_thai', true);
        }
        
        if ($this->san_pham_ap_dung) {
            return SanPham::whereIn('ma_san_pham', $this->san_pham_ap_dung);
        }
        
        if ($this->danh_muc_ap_dung) {
            return SanPham::whereIn('ma_danh_muc', $this->danh_muc_ap_dung);
        }
        
        return SanPham::whereRaw('1 = 0'); // No products
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('trang_thai', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('ngay_bat_dau', '<=', $now)
                    ->where('ngay_ket_thuc', '>=', $now);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('ma_code', $code);
    }

    // Helper methods
    public function isValid()
    {
        $now = Carbon::now();
        return $this->trang_thai && 
               $this->ngay_bat_dau <= $now && 
               $this->ngay_ket_thuc >= $now;
    }

    public function canUse($userId = null)
    {
        if (!$this->isValid()) {
            return false;
        }

        // Check usage limit
        if ($this->gioi_han_su_dung && $this->so_luong_su_dung >= $this->gioi_han_su_dung) {
            return false;
        }

        // Check user usage limit
        if ($userId && $this->gioi_han_moi_khach) {
            $userUsage = LichSuKhuyenMai::where('ma_khuyen_mai', $this->ma_khuyen_mai)
                                      ->where('ma_tai_khoan', $userId)
                                      ->count();
            if ($userUsage >= $this->gioi_han_moi_khach) {
                return false;
            }
        }

        return true;
    }

    public function calculateDiscount($orderValue, $products = [])
    {
        if (!$this->canUse()) {
            return 0;
        }

        if ($orderValue < $this->don_hang_toi_thieu) {
            return 0;
        }

        $discountValue = 0;

        switch ($this->loai_khuyen_mai) {
            case 'percent':
                $discountValue = ($orderValue * $this->gia_tri) / 100;
                if ($this->gia_tri_toi_da && $discountValue > $this->gia_tri_toi_da) {
                    $discountValue = $this->gia_tri_toi_da;
                }
                break;
                
            case 'fixed':
                $discountValue = $this->gia_tri;
                if ($discountValue > $orderValue) {
                    $discountValue = $orderValue;
                }
                break;
                
            case 'product_discount':
                // Calculate discount for applicable products only
                $applicableValue = 0;
                foreach ($products as $product) {
                    if ($this->isProductApplicable($product['ma_san_pham'])) {
                        $applicableValue += $product['gia'] * $product['so_luong'];
                    }
                }
                $discountValue = ($applicableValue * $this->gia_tri) / 100;
                if ($this->gia_tri_toi_da && $discountValue > $this->gia_tri_toi_da) {
                    $discountValue = $this->gia_tri_toi_da;
                }
                break;
        }

        return round($discountValue, 2);
    }

    public function isProductApplicable($productId)
    {
        if ($this->ap_dung_tat_ca) {
            return true;
        }

        if ($this->san_pham_ap_dung && in_array($productId, $this->san_pham_ap_dung)) {
            return true;
        }

        if ($this->danh_muc_ap_dung) {
            $product = SanPham::find($productId);
            if ($product && in_array($product->ma_danh_muc, $this->danh_muc_ap_dung)) {
                return true;
            }
        }

        return false;
    }

    public function getLoaiKhuyenMaiTextAttribute()
    {
        $types = [
            'percent' => 'Giảm theo phần trăm',
            'fixed' => 'Giảm số tiền cố định',
            'product_discount' => 'Giảm giá sản phẩm'
        ];
        
        return $types[$this->loai_khuyen_mai] ?? 'Không xác định';
    }

    public function getTrangThaiTextAttribute()
    {
        if (!$this->trang_thai) {
            return 'Đã tắt';
        }

        $now = Carbon::now();
        
        if ($this->ngay_bat_dau > $now) {
            return 'Chưa bắt đầu';
        }
        
        if ($this->ngay_ket_thuc < $now) {
            return 'Đã kết thúc';
        }
        
        return 'Đang hoạt động';
    }

    public function getGiaTriDisplayAttribute()
    {
        if ($this->loai_khuyen_mai === 'percent') {
            return $this->gia_tri . '%';
        }
        
        return number_format($this->gia_tri, 0, ',', '.') . ' VNĐ';
    }
}
