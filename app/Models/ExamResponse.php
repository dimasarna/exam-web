<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResponse extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer_option_id',
        'text_response',
        'points_earned',
    ];

    public $timestamps = false;

    public function attempt() {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }
    
    public function question() {
        return $this->belongsTo(Question::class);
    }
    
    public function answerOption() {
        return $this->belongsTo(AnswerOption::class);
    }
}
