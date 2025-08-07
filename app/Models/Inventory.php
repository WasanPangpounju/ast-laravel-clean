<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
'refId' ,
'emp' ,
'inventoryName' ,
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
