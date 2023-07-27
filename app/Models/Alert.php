<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;
    protected $table = 'alerts';
    protected $fillable =[
        'AID',
        'ANAME',
        'DATEISUE',
        'DATEALERT',
        'CONTENT',
        'NOTE',
        'DEL',
     ];
     protected $primaryKey = 'AID';
}
