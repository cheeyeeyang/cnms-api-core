<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = [
         'PID',
         'UID',
         'CID',
         'TARGET',
         'ACTUAL',
         'PERCENTAGE',
         'LAT',
         'LNG',
         'STATUS'
    ];
    protected $primaryKey = 'PID';
    public function customer(){
        return $this->belongsTo('App\Models\Customer','CID', 'CID');
    }
}