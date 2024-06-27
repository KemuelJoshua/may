<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'parentId',
        'name',
        'lastname',
        'position',
        'image',
    ];

    // Define a parent relationship
    public function parent()
    {
        return $this->belongsTo(OrganizationMember::class, 'parentId');
    }

    // Define a children relationship
    public function children()
    {
        return $this->hasMany(OrganizationMember::class, 'parentId');
    }
}
