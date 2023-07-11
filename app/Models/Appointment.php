<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $table = 'appointments';
    protected $fillable =[
        'APID',
        'APDATE',
        'TRACK',
        'PID',
        'UID',
        'LAT',
        'LNG'
     ];
     protected $primaryKey = 'APID';
     public function plan(){
         return $this->belongsTo('App\Models\Plan','PID', 'PID');
     }
}
