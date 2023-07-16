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
    ];
    protected $primaryKey = 'ODID';
}
