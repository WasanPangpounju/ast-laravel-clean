<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Astpackageoutside extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver',
    'emp', 
    'ref_id', 
    'supplier_name', 
    'spool', 
    'spool_type', 
    'sack', 
    'sack_type', 
    'box', 
    'box_type', 
    'pallet', 
    'pallet_type', 
    'partition', 
    'partition_type', 
    'package_status'
    ];
}
