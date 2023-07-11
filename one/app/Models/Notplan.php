<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notplan extends Model
{
    use HasFactory;
    protected $fillable = [
        'NPID',
        'NPDATE',
        'UID',
        'CID',
        'LOCATION'
    ];
}
