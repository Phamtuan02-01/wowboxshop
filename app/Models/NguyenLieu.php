<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NguyenLieu extends Model
{
    use HasFactory;

    protected $table = 'nguyen_lieu';
    protected $primaryKey = 'ma_nguyen_lieu';
    
    protected $fillable = [
        'ten_nguyen_lieu',
        'ma_nhom_nguyen_lieu',
        'hinh_anh_url',
        'gia',
        'khoi_luong_tinh',
        'calo',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'khoi_luong_tinh' => 'decimal:2',
        'calo' => 'integer',
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];

    // Relationships
    public function nhomNguyenLieu()
    {
        return $this->belongsTo(NhomNguyenLieu::class, 'ma_nhom_nguyen_lieu', 'ma_nhom_nguyen_lieu');
    }

    public function sanPhams()
    {
        return $this->belongsToMany(SanPham::class, 'san_pham_nguyen_lieu', 'ma_nguyen_lieu', 'ma_san_pham')
                    ->withPivot('so_luong')
                    ->withTimestamps();
    }

    public function chiTietNguyenLieuDonHangs()
    {
        return $this->hasMany(ChiTietNguyenLieuDonHang::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }

    public function chiTietNguyenLieuGioHangs()
    {
        return $this->hasMany(ChiTietNguyenLieuGioHang::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }
}
