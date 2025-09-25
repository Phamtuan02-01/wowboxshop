<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TaiKhoan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tai_khoan';
    protected $primaryKey = 'ma_tai_khoan';
    
    protected $fillable = [
        'ten_dang_nhap',
        'email',
        'so_dien_thoai',
        'mat_khau_hash',
        'ma_vai_tro',
        'ma_dia_chi_mac_dinh',
    ];

    protected $hidden = [
        'mat_khau_hash',
    ];

    protected $casts = [
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];

    // Relationships
    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'ma_vai_tro', 'ma_vai_tro');
    }

    public function diaChiMacDinh()
    {
        return $this->belongsTo(DiaChi::class, 'ma_dia_chi_mac_dinh', 'ma_dia_chi');
    }

    public function diaChis()
    {
        return $this->hasMany(DiaChi::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }

    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }

    public function gioHang()
    {
        return $this->hasOne(GioHang::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }
}
