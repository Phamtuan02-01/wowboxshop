<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    use HasFactory;

    protected $table = 'don_hang';
    protected $primaryKey = 'ma_don_hang';
    
    // Sử dụng tên cột timestamp tùy chỉnh
    const CREATED_AT = 'ngay_tao';
    const UPDATED_AT = 'ngay_cap_nhat';
    
    protected $fillable = [
        'ma_tai_khoan',
        'ngay_dat_hang',
        'trang_thai',
        'tong_tien',
        'ma_dia_chi',
        'phuong_thuc_giao_hang',
        'phuong_thuc_thanh_toan',
        'ho_ten',
        'so_dien_thoai',
        'dia_chi',
        'tinh_thanh_pho',
        'cua_hang_nhan',
        'ghi_chu',
        'ma_thanh_toan',
        'url_thanh_toan',
        'ma_khuyen_mai',
        'giam_gia_khuyen_mai',
        'ly_do_huy',
        'ngay_huy',
    ];

    // Tạo ID tự động cho ma_don_hang
    public $incrementing = true;

    protected $casts = [
        'ngay_dat_hang' => 'datetime',
        'ngay_huy' => 'datetime',
        'tong_tien' => 'decimal:2',
        'giam_gia_khuyen_mai' => 'decimal:2',
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

    public function khuyenMai()
    {
        return $this->belongsTo(KhuyenMai::class, 'ma_khuyen_mai', 'ma_khuyen_mai');
    }

    // Accessors
    public function getTrangThaiTextAttribute()
    {
        $trangThaiTexts = [
            'cho_xac_nhan' => 'Chờ xác nhận',
            'da_giao' => 'Đã giao',
            'da_huy' => 'Đã hủy'
        ];
        
        return $trangThaiTexts[$this->trang_thai] ?? 'Chờ xác nhận';
    }

    // Helper method to get status badge class
    public function getStatusBadgeClass()
    {
        $statusClasses = [
            'cho_xac_nhan' => 'warning',
            'da_giao' => 'success',
            'da_huy' => 'danger'
        ];
        
        return $statusClasses[$this->trang_thai] ?? 'secondary';
    }
}
