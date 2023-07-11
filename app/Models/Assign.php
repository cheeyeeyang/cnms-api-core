<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assign extends Model
{
    use HasFactory;
    protected $table = 'assigns';
    protected $fillable =[
        'AID',
        'ZID',
        'EMPID',
     ];
     protected $primaryKey = 'AID';
     public function user(){
         return $this->belongsTo('App\Models\User','UID', 'UID');
     }
     public function employee(){
        return $this->belongsTo('App\Models\Employee','EMPID', 'EMPID');
    }
}
