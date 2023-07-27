<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlertTransaction extends Model
{
    use HasFactory;
    protected $table = 'alert_transactoins';
    protected $fillable =[
        'AID',
        'UID',
     ];
}
