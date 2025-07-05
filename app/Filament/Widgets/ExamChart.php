<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\Role;

class ExamChart extends ChartWidget
{
    protected static ?string $heading = 'Hasil Ujian';

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        if (is_null($activeFilter) || $activeFilter === '') {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        } else {
            $totalMembers = Exam::find($activeFilter)
                ->classroom
                ->users;
            
            $examAttempts = ExamAttempt::where('exam_id', $activeFilter)
                ->with('result')
                ->get()
                ->groupBy('user_id');
            
            $passed = 0;
            $notPassed = 0;
            $notTaken = 0;

            foreach ($totalMembers as $user) {
                $attempts = $examAttempts->get($user->id);
                if ($attempts && $attempts->count() > 0) {
                    // Check if any attempt has result->passed == true
                    $hasPassed = $attempts->contains(function ($attempt) {
                        return $attempt->result && $attempt->result->passed;
                    });

                    if ($hasPassed) {
                        $passed++;
                    } else {
                        $notPassed++;
                    }
                } else {
                    $notTaken++;
                }
            }

            return [
                'datasets' => [
                    [
                        'data' => [
                            $passed,
                            $notPassed,
                            $notTaken,
                        ], 'backgroundColor' => [
                            'rgb(75, 192, 192)', // Lulus
                            'rgb(255, 99, 132)', // Tidak Lulus
                            'rgb(255, 205, 86)', // Belum Mengerjakan
                        ],
                    ]
                ],
                'labels' => ['Lulus', 'Tidak Lulus', 'Belum Mengerjakan'],
            ];
        }
    }

    protected function getFilters(): array
    {
        $exams = Exam::whereHas('classroom.users', function($q) {
            $q->where('user_id', auth()->id());
        })->pluck('title', 'id');

        $filters = $exams->isEmpty()
            ? ['' => 'Tidak ada ujian tersedia']
            : $exams->prepend('Pilih Ujian', '')->toArray();
        
        return $filters;
    }

    protected function getType(): string
    {
        return 'pie';
    }

    public static function canView(): bool
    {
        return auth()->user()->role_id == Role::IS_ADMINISTRATOR ||
            auth()->user()->role_id == Role::IS_PENGAJAR;
    }
}
