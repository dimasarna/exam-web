<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassEnrollment extends Model
{
    protected $table = 'class_enrollments';

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
