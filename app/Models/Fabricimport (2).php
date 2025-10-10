<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricimport extends Model
{
    use HasFactory;

        // ชี้ชื่อตารางให้ตรงกับที่คุณสร้าง “fabricimport” (ไม่มี s)
    protected $table = 'fabricimport';

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
        'supplier_name',
        'invoice_no',
        'unit_price',
        'dye_lot',
        'location',
        'SONumber',
    ];
    
}
