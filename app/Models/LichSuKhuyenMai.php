<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuKhuyenMai extends Model
{
    use HasFactory;

    protected $table = 'lich_su_khuyen_mai';
    
    protected $fillable = [
        'ma_khuyen_mai',
        'ma_don_hang',
        'ma_tai_khoan',
        'gia_tri_giam',
        'ma_code_su_dung',
        'ngay_su_dung'
    ];

    protected $casts = [
        'ngay_su_dung' => 'datetime',
    ];

    // Relationships
    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'ma_khuyen_mai', 'ma_khuyen_mai');
    }

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'ma_tai_khoan', 'ma_tai_khoan');
    }
}
