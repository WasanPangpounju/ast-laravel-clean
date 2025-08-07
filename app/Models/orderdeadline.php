<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderdeadline extends Model
{
    use HasFactory;

    protected $fillable = [
'purchaseOrder',
'round',
'dt',
'ordery',
'orderp',
        ];

}
