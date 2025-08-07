<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AstPurchaseorder extends Model
{
    use HasFactory;
    protected $fillable = [
'id',
            'emp',
                'createDate',
    'customerName',
    'fabricId',
    'fabricPattern' ,
    'fabricStructure' ,
    'orderSumYard' , 
    'orderSumM' , 
    'fabricSPY' , 
    'fabricSpP' ,
        'priceYard' ,
    'priceM' ,
    'discountP' ,
    'discountYard' ,
    'commission' ,
    'vat' ,
    'purchaseOrder' ,
    'po' ,
    'deadline',
    'comment',
        ];
            
}
