<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    use HasFactory;

    protected $table = 'danh_muc';
    protected $primaryKey = 'ma_danh_muc';
    
    protected $fillable = [
        'ten_danh_muc',
        'ma_danh_muc_cha',
    ];

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'ma_danh_muc';
    }

    // Relationships
    public function danhMucCha()
    {
        return $this->belongsTo(DanhMuc::class, 'ma_danh_muc_cha', 'ma_danh_muc');
    }

    public function danhMucCons()
    {
        return $this->hasMany(DanhMuc::class, 'ma_danh_muc_cha', 'ma_danh_muc');
    }

    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, 'ma_danh_muc', 'ma_danh_muc');
    }
}
