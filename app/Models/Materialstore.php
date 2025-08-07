<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materialstore extends Model
{
    use HasFactory;
    protected $fillable = [
'withdrawId',
'department',
'emp',
'supplierName',
'yarnType',
'lot',
'spool',
'weight_p_net',
'weight_kg_net',
'average_p',
'average_kg',
'createDate'
];

}
