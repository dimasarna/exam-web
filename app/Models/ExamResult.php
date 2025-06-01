<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $fillable = [
        'attempt_id',
        'total_points',
        'points_earned',
        'percentage',
        'passed',
        'completed_at',
    ];

    public $timestamps = false;

    public function attempt() {
        return $this->belongsTo(ExamAttempt::class, 'attempt_id');
    }
}
