<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietGioHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_gio_hang';
    protected $primaryKey = 'ma_chi_tiet_gio_hang';
    
    protected $fillable = [
        'ma_gio_hang',
        'ma_san_pham',
        'ma_bien_the',
        'so_luong',
    ];

    protected $casts = [
        'so_luong' => 'integer',
    ];

    // Relationships
    public function gioHang()
    {
        return $this->belongsTo(GioHang::class, 'ma_gio_hang', 'ma_gio_hang');
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
        return $this->hasMany(ChiTietNguyenLieuGioHang::class, 'ma_chi_tiet_gio_hang', 'ma_chi_tiet_gio_hang');
    }
}
