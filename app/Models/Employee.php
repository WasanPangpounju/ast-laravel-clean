<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'empID',
        'name',
        'ssn',
        'tel',
        'gender',
        'berthdate',
        'address',
        'description',
        'department',
        'jobdescription',
        'manager',
        'createEmployeeBy',
        'added_on',
    ];

}
