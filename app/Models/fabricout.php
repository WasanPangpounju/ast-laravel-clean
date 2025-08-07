<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fabricout extends Model
{
    use HasFactory;

    protected $fillable = [
            'createDate',
            'no' ,
            'refId',
            'emp' ,
                        'customerName' ,
            'receiveName',
            'fabricStruct',
            'fabricPattern',
            'fabricW',
            'customerReplace',
            'fabricStructReplace',
'vatNo',
'vatType',
            'fold',
            'sumYard',
            'comment',
            'receiveType',
            'orderId',
    ];

}
