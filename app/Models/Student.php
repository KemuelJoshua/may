<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $guard = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'user_id',
        'firstname',
        'lastname',
        'mi',
        'age',
        'birthday'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
