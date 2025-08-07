<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materialstock extends Model
{
    use HasFactory;
    protected $fillable = [
'createDate',
'yarnType',
'supplier',
'lot',
'spool',
'weight_p_net',
'weight_kg_net',
'average_p',
'average_kg',
];


}
