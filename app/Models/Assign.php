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
     public function zone(){
         return $this->belongsTo('App\Models\Zone','ZID', 'ZID');
     }
     public function employee(){
        return $this->belongsTo('App\Models\Employee','EMPID', 'EMPID');
    }
}
