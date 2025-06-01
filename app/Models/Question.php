<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'exam_id',
        'question_type',
        'text',
        'points',
        'order_position',
        'explanation',
        'created_by'
    ];

    public function exam() {
        return $this->belongsTo(Exam::class);
    }
    
    public function answerOptions() {
        return $this->hasMany(AnswerOption::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
