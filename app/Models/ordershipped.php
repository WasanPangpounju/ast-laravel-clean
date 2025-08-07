<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordershipped extends Model
{
    use HasFactory;

    protected $fillable = [
        'refId' ,
        'emp' ,
        'staff' ,
        'customerName',
        'exceptName' ,
                'orderId' ,
        'createDate' ,
        'fabricId' ,
        'fabricStruct' ,
        'fold' ,
        'sumYard' ,
        'sumM' ,
        'fabricW' ,
        'comment' 
    ];
        
}
