<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamAttempt extends Model
{
    protected $fillable = [
        'exam_id',
        'user_id',
        'started_at',
        'submitted_at',
        'status',
    ];

    public $timestamps = false;

    protected function casts() {
        return [
            'started_at' => 'datetime'
        ];
    }
    
    public function exam() {
        return $this->belongsTo(Exam::class);
    }
    
    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function responses() {
        return $this->hasMany(ExamResponse::class, 'attempt_id');
    }
    
    public function result() {
        return $this->hasOne(ExamResult::class, 'attempt_id');
    }
}
