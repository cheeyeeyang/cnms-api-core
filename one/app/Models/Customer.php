<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable =[
        'CID',
        'CNAME',
        'TEL',
        'LOCATION',
        'ZID'
     ];
     protected $primaryKey = 'CID';
     public function zone(){
         return $this->belongsTo('App\Models\Zone','ZID', 'ZID');
     }
}
