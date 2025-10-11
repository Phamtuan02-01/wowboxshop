<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TaiKhoan extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'tai_khoan';
    protected $primaryKey = 'ma_tai_khoan';
    
    // Chỉ định tên cột timestamps tùy chỉnh
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';
    
    protected $fillable = [
        'ten_dang_nhap',
        'email',
        'ho_ten',
        'so_dien_thoai',
        'mat_khau',
        'mat_khau_hash',
        'ma_vai_tro',
        'ma_dia_chi_mac_dinh',
        'ngay_tao',
        'ngay_cap_nhat',
    ];

    protected $hidden = [
        'mat_khau_hash',
    ];

    // Laravel Auth yêu cầu password field
    public function getAuthPassword()
    {
        return $this->mat_khau_hash;
    }

    // Laravel Auth yêu cầu username field
    public function getAuthIdentifierName()
    {
        return 'ten_dang_nhap';
    }

    public function getAuthIdentifier()
    {
        return $this->ten_dang_nhap;
    }

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
