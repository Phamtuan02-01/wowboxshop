<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_don_hang';
    protected $primaryKey = 'ma_chi_tiet_don_hang';
    
    protected $fillable = [
        'ma_don_hang',
        'ma_san_pham',
        'ma_bien_the',
        'so_luong',
        'gia_tai_thoi_diem_mua',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'gia_tai_thoi_diem_mua' => 'decimal:2',
    ];

    // Relationships
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'ma_san_pham', 'ma_san_pham');
    }

    public function bienThe()
    {
        return $this->belongsTo(BienTheSanPham::class, 'ma_bien_the', 'ma_bien_the');
    }

    public function chiTietNguyenLieus()
    {
        return $this->hasMany(ChiTietNguyenLieuDonHang::class, 'ma_chi_tiet_don_hang', 'ma_chi_tiet_don_hang');
    }
}
