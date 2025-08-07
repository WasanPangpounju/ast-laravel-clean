<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Htrpackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp', 
        'supplier_name', 
        'spool', 
        'sack', 
        'box', 
        'pallet',
'partition',
'spool_paper',
'spool_plastic',
'spoolC_plastic',
'spoolC_paper',
'pallet_wood',
'pallet_steel',
'createDate' ];

}
