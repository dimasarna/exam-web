<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'image',
        'content',
        'is_active',
        'created_by',
    ];
}
