<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class production extends Model
{
    use HasFactory;

    protected $fillable = [

        'refId',
    'emp',
    'createDate',
        'customerName',
    'coname',
    'fabricId',
    'fabricPattern',
    'fabricStructure',
    'yarn_h_count',
    'fabric_w',
    'orderSumYard' ,
    'orderSumM',
    'typrtag' ,
    'fabricSPY' ,
    'fabricSpP' ,
    'typemachine' ,
    'machinenumber' ,
    'purchaseOrder' ,
    'po' ,
    'comment',
    'comment2',
        'payment' ,
    
    'yarnHType1' ,
        'subNameH1' ,
    'yarnHCount1' ,
    'yarnHRatio1' , 
    
        'yarnHType2' ,
'subNameH2' , 
    'yarnHCount2' ,
    
    'yarnWType1' ,
    'subNameW1' ,
    'yarnWCount1' ,
    'yarnWType2' ,
        'subNameW2' ,
    'yarnWCount2' ,
    'yarnWType3' ,
    'subNameW3' ,
    'yarnWCount3' ,
    'yarnWType4' ,
'subNameW4' ,
    'yarnWCount4' ,
    ];                

}
