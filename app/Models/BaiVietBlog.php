<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaiVietBlog extends Model
{
    use HasFactory;

    protected $table = 'bai_viet_blog';
    protected $primaryKey = 'ma_bai_viet';
    
    protected $fillable = [
        'tieu_de',
        'noi_dung',
        'hinh_anh_url',
        'ngay_dang',
    ];

    protected $casts = [
        'ngay_dang' => 'datetime',
        'ngay_tao' => 'datetime',
        'ngay_cap_nhat' => 'datetime',
    ];
}
