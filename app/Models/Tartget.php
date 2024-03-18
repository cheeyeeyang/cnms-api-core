<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tartget extends Model
{
    use HasFactory;
    protected $fillable =[
        'TGID',
        'UID',
        'TARGET',
        'AMOUNT',
        'PERCENTAGE',
     ];
     protected $primaryKey = 'TGID';
     public function user(){
         return $this->belongsTo('App\Models\User','UID', 'UID');
     }
}
