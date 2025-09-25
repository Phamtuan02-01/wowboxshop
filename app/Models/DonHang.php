<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang';
    protected $primaryKey = 'ma_don_hang';
    
    protected $fillable = [
        'ma_tai_khoan',
        'ngay_dat_hang',
        'trang_thai',
        'tong_tien',
        'ma_dia_chi',
    ];

    protected $casts = [
        'ngay_dat_hang' => 'datetime',
        'tong_tien' => 'decimal:2',
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];

    // Relationships
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }

    public function diaChi()
    {
        return $this->belongsTo(DiaChi::class, 'ma_dia_chi', 'ma_dia_chi');
    }

    public function chiTietDonHangs()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_don_hang', 'ma_don_hang');
    }
}
