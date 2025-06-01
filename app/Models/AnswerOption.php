<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnswerOption extends Model
{
    protected $fillable = [
        'question_id',
        'text',
        'is_correct',
        'order_position',
        'created_by'
    ];

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'created_by');
    }
}
