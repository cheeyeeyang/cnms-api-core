<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
           'ODID',
           'ORID',
           'QTY',
           'FREEQTY',
           'UID',
           'PRICE',
           'PDID',
           'created_at',
           'updated_at'
    ];
    protected $primaryKey = 'ODID';
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'PDID', 'PDID');
    }
}
