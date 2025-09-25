<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhomNguyenLieu extends Model
{
    use HasFactory;

    protected $table = 'nhom_nguyen_lieu';
    protected $primaryKey = 'ma_nhom_nguyen_lieu';
    
    protected $fillable = [
        'ten_nhom',
    ];

    // Relationship: Một nhóm có nhiều nguyên liệu
    public function nguyenLieus()
    {
        return $this->hasMany(NguyenLieu::class, 'ma_nhom_nguyen_lieu', 'ma_nhom_nguyen_lieu');
    }
}
