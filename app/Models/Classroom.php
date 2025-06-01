<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
        'created_by',
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'class_enrollments')
            ->withPivot('enrolled_at');
    }
    
    public function exams() {
        return $this->hasMany(Exam::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
