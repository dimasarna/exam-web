<?php

namespace App\Filament\App\Resources\ExamResource\Pages;

use Illuminate\Support\Collection;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamResponse;
use App\Models\ExamResult;
use App\Models\Question;
use App\Models\AnswerOption;
use App\Filament\App\Resources\ExamResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Renderless;

class TakeExam extends Page
{
    use InteractsWithRecord;

    protected static ?string $title = '';

    protected static string $resource = ExamResource::class;

    protected static string $view = 'filament.app.resources.exam-resource.pages.take-exam';

    public int $duration = 0;

    public int $current_question = 0;

    public int $total_questions = 0;

    public string $status = '';

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        if ($this->attempts->count() > 0) {
            $attemptInProgress = $this->attempts
                ->where('status', 'in_progress')
                ->first();
            
            if (is_null($attemptInProgress)) {
                $this->status = 'submitted';
            } else $this->start();
        } else $this->start();
    }

    public function start() {
        if ($this->attempts->count() >= $this->record->max_attempts) {
            Notification::make()
                ->title('Anda sudah mencapai batas maksimal percobaan ujian.')
                ->danger()
                ->send();
        } else {
            
            $this->duration = now()->diffInSeconds($this->attempt
                ->started_at
                ->addMinutes($this->record->duration_minutes));

            if ($this->duration < 0)
                $this->submit();
            else {
                $this->current_question = 1;
                $this->total_questions = $this->questions->count();
                $this->status = 'in_progress';
            }
        }
    }

    #[Renderless]
    public function check($option_id)
    {
        $question = $this->questions[$this->current_question - 1];
        $option = $question->answerOptions->findOrFail($option_id);
        
        $this->attempt->responses()->updateOrCreate([
            'attempt_id' => $this->attempt->id,
            'question_id' => $question->id,
        ], [
            'answer_option_id' => $option_id,
            'points_earned' => $option->is_correct ? $question->points : 0,
        ]);

        $this->dispatch('answer-selected', ['selectedId' => $option_id]);
    }

    public function next()
    {
        if ($this->current_question < $this->total_questions) {
            $this->current_question++;
        } else {
            $this->submit();
        }
    }

    public function submit()
    {
        $points_earned = $this->attempt->responses->sum('points_earned');
        $max_points = $this->questions->sum('points');

        $this->attempt->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        $this->attempt->result()->updateOrCreate([
            'attempt_id' => $this->attempt->id,
        ], [
            'points_earned' => $points_earned,
            'total_points' => $max_points,
            'percentage' => ($points_earned / $max_points) * 100,
            'passed' => ($points_earned >= $this->record->passing_score) ? true : false,
            'completed_at' => now(),
        ]);

        $this->status = 'submitted';
    }

    public function cancel()
    {
        return redirect(ExamResource::getUrl('index'));
    }

    #[Computed]
    public function attempts()
    {
        return $this->record
            ->attempts
            ->where('user_id', auth()->id())
            ->load('responses', 'result');
    }

    #[Computed]
    public function attempt()
    {
        return ExamAttempt::firstOrCreate([
            'exam_id' => $this->record->id,
            'user_id' => auth()->id(),
            'status' => 'in_progress',
        ], ['started_at' => now()]);
    }

    #[Computed]
    public function questions()
    {
        $questions = $this->record
            ->questions
            ->load('answerOptions')
            ->sortBy('order_position');
        
        $total = min($questions->count(), $this->record->total_questions);
        
        if ($this->record->shuffle_questions)
            return $questions->random($total);
        else return $questions->take($total);
    }
}
