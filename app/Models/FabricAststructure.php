<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricAststructure extends Model
{
    use HasFactory;
    protected $fillable = [
        'vat' ,
        'purchaseOrder' ,
    'yarnHType1' ,
    'subNameH1' ,
    'yarnHCount1' ,
    'yarnHRatio1' ,
    'yarnHType2' ,
    'subNameH2' ,
    'yarnHCount2' ,
    'yarnHRatio2' ,
    'yarnWType1' ,
    'subNameW1' ,
    'yarnWCount1' ,
    'yarnWRatio1' ,
    'yarnWType2' ,
    'subNameW2' ,
    'yarnWCount2' ,
    'yarnWRatio2' ,
    'yarnWType3' ,
    'subNameW3' ,
    'yarnWCount3' ,
    'yarnWRatio3' ,
    'yarnWType4' ,
    'subNameW4' ,
    'yarnWCount4' ,
    'yarnWRatio4' ,
    ];

}
