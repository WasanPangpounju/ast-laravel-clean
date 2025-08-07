<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empmaterial extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'tel',
        'department',
    ];

}
