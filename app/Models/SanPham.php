<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'so_luong_ton_kho',
        'thuong_hieu',
        'xuat_xu',
        'thong_tin_dinh_duong',
        'khoi_luong_tinh',
        'calo',
        'hinh_anh',
        'hinh_anh_phu',
        'trang_thai',
        'la_noi_bat',
        'luot_xem',
        'diem_danh_gia_trung_binh'
    ];

    protected $casts = [
        'gia' => 'decimal:0',
        'gia_khuyen_mai' => 'decimal:0',
        'so_luong_ton_kho' => 'integer',
        'khoi_luong_tinh' => 'integer',
        'calo' => 'integer',
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
}
