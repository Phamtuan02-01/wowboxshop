<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienTheSanPham extends Model
{
    use HasFactory;

    protected $table = 'bien_the_san_pham';
    protected $primaryKey = 'ma_bien_the';
    
    // Custom timestamp columns
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';
    
    protected $fillable = [
        'ma_san_pham',
        'kich_thuoc',
        'gia',
        'calo',
        'so_luong_ton',
        'trang_thai',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'calo' => 'integer',
        'so_luong_ton' => 'integer',
        'trang_thai' => 'boolean',
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];

    // Relationships
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_bien_the', 'ma_bien_the');
    }

    public function chiTietGioHangs()
    {
        return $this->hasMany(ChiTietGioHang::class, 'ma_bien_the', 'ma_bien_the');
    }

    // Accessors & Mutators
    public function getTrangThaiTextAttribute()
    {
        return $this->trang_thai ? 'Hoạt động' : 'Không hoạt động';
    }

    public function getTrangThaiClassAttribute()
    {
        return $this->trang_thai ? 'success' : 'danger';
    }

    public function getStockStatusAttribute()
    {
        if ($this->so_luong_ton <= 0) {
            return ['text' => 'Hết hàng', 'class' => 'danger'];
        } elseif ($this->so_luong_ton <= 10) {
            return ['text' => 'Sắp hết', 'class' => 'warning'];
        } else {
            return ['text' => 'Còn hàng', 'class' => 'success'];
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('trang_thai', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('so_luong_ton', '>', 0);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('ma_san_pham', $productId);
    }
}
