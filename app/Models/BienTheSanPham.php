<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BienTheSanPham extends Model
{
    use HasFactory;

    protected $table = 'bien_the_san_pham';
    protected $primaryKey = 'ma_bien_the';
    
    protected $fillable = [
        'ma_san_pham',
        'kich_thuoc',
        'gia',
        'calo',
    ];

    protected $casts = [
        'gia' => 'decimal:2',
        'calo' => 'integer',
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
}
