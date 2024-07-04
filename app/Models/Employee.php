<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'picture_url',
        'firstname',
        'lastname',
        'middlename',
        'position',
        'gender',
        'phone_number',
        'employee_number',
        'date_started',
        'date_stop',
        'status',
        'address',
        'email_address',
        'isActive'
    ];
}
