<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    protected $fillable = [
        'classroom_id',
        'title',
        'description',
        'total_questions',
        'duration_minutes',
        'passing_score',
        'max_attempts',
        'shuffle_questions',
        'is_active',
        'available_from',
        'available_to',
        'created_by'
    ];

    protected function casts() {
        return [
            'duration_minutes' => 'int'
        ];
    }

    public function classroom() {
        return $this->belongsTo(Classroom::class);
    }
    
    public function questions() {
        return $this->hasMany(Question::class);
    }
    
    public function attempts() {
        return $this->hasMany(ExamAttempt::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isAvailable()
    {
        return $this->is_active &&
            ($this->available_from === null || $this->available_from <= now()) &&
            ($this->available_to === null || $this->available_to >= now());
    }
}
