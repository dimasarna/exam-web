<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name'];

    public const IS_ADMINISTRATOR = 1;
    public const IS_PENGAJAR = 2;
    public const IS_PELAJAR = 3;
}
