<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    use HasFactory;

    protected $table = 'vai_tro';
    protected $primaryKey = 'ma_vai_tro';
    
    protected $fillable = [
        'ten_vai_tro',
    ];

    // Relationship: Một vai trò có nhiều tài khoản
    public function taiKhoans()
    {
        return $this->hasMany(TaiKhoan::class, 'ma_vai_tro', 'ma_vai_tro');
    }
}
