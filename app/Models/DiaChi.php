<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiaChi extends Model
{
    use HasFactory;

    protected $table = 'dia_chi';
    protected $primaryKey = 'ma_dia_chi';
    
    protected $fillable = [
        'ma_tai_khoan',
        'ho_ten',
        'thanh_pho',
        'quan_huyen',
        'dia_chi_chi_tiet',
        'so_dien_thoai',
        'la_mac_dinh',
    ];

    protected $casts = [
        'la_mac_dinh' => 'boolean',
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];

    // Relationships
    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }

    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_dia_chi', 'ma_dia_chi');
    }
}
