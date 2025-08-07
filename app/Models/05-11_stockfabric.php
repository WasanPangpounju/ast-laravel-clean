<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stockfabric extends Model
{
    use HasFactory;

    protected $fillable = [
        'refId' ,
        'emp' ,
        'fabricStruct',
        'fabricPattern',
        'fabricW',
        'fold',
        'sumYard',
        'createDate'
    ];
    
}
