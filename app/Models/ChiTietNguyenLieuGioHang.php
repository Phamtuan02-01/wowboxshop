<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChiTietNguyenLieuGioHang extends Model
{
    use HasFactory;

    protected $table = 'chi_tiet_nguyen_lieu_gio_hang';
    protected $primaryKey = 'ma_chi_tiet_nguyen_lieu_gio_hang';
    
    protected $fillable = [
        'ma_chi_tiet_gio_hang',
        'ma_nguyen_lieu',
        'so_luong',
    ];

    protected $casts = [
        'so_luong' => 'integer',
    ];

    // Relationships
    public function chiTietGioHang()
    {
        return $this->belongsTo(ChiTietGioHang::class, 'ma_chi_tiet_gio_hang', 'ma_chi_tiet_gio_hang');
    }

    public function nguyenLieu()
    {
        return $this->belongsTo(NguyenLieu::class, 'ma_nguyen_lieu', 'ma_nguyen_lieu');
    }
}
