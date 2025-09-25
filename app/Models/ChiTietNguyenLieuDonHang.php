<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietNguyenLieuDonHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_nguyen_lieu_don_hang';
    protected $primaryKey = 'ma_chi_tiet_nguyen_lieu';
    
    protected $fillable = [
        'ma_chi_tiet_don_hang',
        'ma_nguyen_lieu',
        'so_luong',
        'gia_tai_thoi_diem_mua',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'gia_tai_thoi_diem_mua' => 'decimal:2',
    ];

    // Relationships
    public function chiTietDonHang()
    {
        return $this->belongsTo(ChiTietDonHang::class, 'ma_chi_tiet_don_hang', 'ma_chi_tiet_don_hang');
    }

    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }
}
