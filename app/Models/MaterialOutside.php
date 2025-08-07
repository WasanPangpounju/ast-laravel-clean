<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialOutside extends Model
{
    use HasFactory;

    protected $fillable = [
'emp',
'supplierName', 
'createDate' ,
'yarnType', 
'lot', 
'pallet', 
'box', 
'sack', 
'spool', 
'weight_p_sum', 
'weight_kg_sum', 
'weight_p_package', 
'weight_kg_package', 
'weight_p_net', 
'weight_kg_net', 
'average_p', 
'average_kg', 
'recipient', 
'comment',
'paymentComment'
    ];
        
}
