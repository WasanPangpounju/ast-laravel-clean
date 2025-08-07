<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricAst extends Model
{
    use HasFactory;
    protected $fillable = [
        'vat' ,
        'purchaseOrder' ,
        'yarn_h_count' ,
        'fabric_w' ,
        'phewNumber' ,
        'phewW' ,
        'payment',
];

}
