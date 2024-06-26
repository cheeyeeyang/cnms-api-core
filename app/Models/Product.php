<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'PDID',
        'PDNAME',
        'CHEMISTRY_NAME',
        'UNIT_ID',
        'CATE_ID',
        'BUY_PRICE',
        'SALE_PRICE',
        'SUPPLIER',
        'DESCRIPTION',
        'IMAGE',
        'created_at',
        'updated_at',
    ];
    protected $primaryKey = 'PDID';
    public function unit(){
        return $this->belongsTo('App\Models\Unit','UNIT_ID', 'id');
    }
}
