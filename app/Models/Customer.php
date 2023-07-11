<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'CID',
        'CNAME',
        'TEL',
        'VILLNAME',
        'DISNAME',
        'PRONAME',
        'WORK_PLACE',
        'BOD',
        'NOTE',
        'ZID',
        'LAT',
        'LNG',
        'created_at',
        'updated_at'
    ];
    protected $primaryKey = 'CID';
    public function zone()
    {
        return $this->belongsTo('App\Models\Zone', 'ZID', 'ZID');
    }
}
