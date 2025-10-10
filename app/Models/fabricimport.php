<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FabricImport extends Model
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
        'createDate',
        'customer',
        'fabricId',
        supplier_name,
        invoice_no,
        unit_price,
        dye_lot,
        location,
        SONumber,
    ];
    
}
