<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_pham';
    protected $primaryKey = 'ma_san_pham';
    
    protected $fillable = [
        'ten_san_pham',
        'ma_danh_muc',
        'mo_ta',
        'hinh_anh_url',
        'la_noi_bat',
    ];

    protected $casts = [
        'la_noi_bat' => 'boolean',
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];

    // Relationships
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'ma_danh_muc', 'ma_danh_muc');
    }

    public function nguyenLieus()
    {
        return $this->belongsToMany(NguyenLieu::class, 'san_pham_nguyen_lieu', 'ma_san_pham', 'ma_nguyen_lieu')
                    ->withPivot('so_luong')
                    ->withTimestamps();
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
}
