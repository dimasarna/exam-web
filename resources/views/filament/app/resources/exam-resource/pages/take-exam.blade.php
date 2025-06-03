<x-filament-panels::page>
    @vite(['resources/css/app.css', 'resources/css/exam.css'])

    <div class="mx-auto flex flex-row items-center justify-center">
        @livewire('notifications')
        
        <!-- Exam Container -->
        @if ($status === 'in_progress')
        <div class="p-6 mb-4">
            <header class="flex flex-col mb-4"
                x-data="{
                    id: null,
                    remaining: 0,
                    init() {
                        if (!this.id) {
                            this.remaining = {{ $this->duration }};

                            this.id = setInterval(() => {
                                if (this.remaining <= 0) {
                                    clearInterval(this.id)
                                    $wire.submit();
                                } else this.remaining--;
                            }, 1000);
                        }
                    },
                    formatTime(time) {
                        const minutes = Math.floor(time / 60);
                        const seconds = time % 60;
                        return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                    }
                }">
                <div class="rounded-full bg-primary-600 p-3">
                    <p class="text-center text-white text-2xl font-bold" x-text="formatTime(remaining)"></p>
                </div>
            </header>

            <div class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 p-6">
                <div class="flex justify-between mb-4">
                    <p class="text-md font-bold">SOAL</p>
                    <p class="text-sm">{{ $this->current_question }} dari {{ $this->total_questions }}</p>
                </div>
                <div class="mb-4">
                    <p class="text-md font-bold">{!! $this->questions[$this->current_question - 1]->text !!}</p>
                </div>
                <div class="mb-4">
                    <p class="text-md font-bold mb-4">PILIHAN JAWABAN</p>
                    <ul class="flex flex-col rounded-lg gap-4">
                        @foreach ($this->questions[$this->current_question - 1]->answerOptions as $option)
                        <li id="option.{{ $option->id }}"
                            class="option rounded-lg p-3 text-sm ring-1 ring-gray-300 hover:bg-gray-100 cursor-pointer"
                            wire:click="check({{ $option->id }})"
                        >    
                            <p>{{ $option->text }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="flex flex-row-reverse mt-3">
                @if ($current_question == $this->total_questions)
                <button class="btn rounded-xl p-3 px-4 text-white font-bold" wire:click="submit">
                    Selesaikan Ujian
                </button>
                @else
                <button class="btn rounded-xl p-3 px-4 text-white font-bold" wire:click="next">
                    Selanjutnya
                </button>
                @endif
            </div>
        </div>
        @endif

        <!-- Result Container -->
        @if ($status === 'submitted')
        <div
            class="flex flex-col rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 p-6 mb-4"
            x-data="{
                formatTime(time) {
                    const minutes = Math.floor(time / 60);
                    const seconds = time % 60;
                    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                }
            }">
            <header class="flex flex-col mb-2">
                <h2 class="text-md font-bold">Hasil Ujian</h2>
            </header>
            <div class="flex flex-col mb-4 items-center border-b">
                <p class="text-md">Nilai Tertinggi</p>
                <h1 class="text-3xl font-bold mb-4 {{ ($this->attempts->max('result.points_earned') >= $this->getRecord()->passing_score) ? 'text-green-600' : 'text-red-600' }}">
                    {{ $this->attempts->max('result.points_earned') }}
                </h1>
            </div>
            <ul class="grid md:grid-cols-2 gap-4">
                <li>
                    <h6>Kesempatan Ujian Ulang</h6>
                    <p class="text-md font-bold">{{ $this->getRecord()->max_attempts }}</p>
                </li>
                <li>
                    <h6>Minimal Nilai Kelulusan</h6>
                    <p class="text-md font-bold">{{ $this->getRecord()->passing_score }}</p>
                </li>
                <li>
                    <h6>Durasi Ujian</h6>
                    <p class="text-md font-bold" x-text="formatTime({{ $this->getRecord()->duration_minutes * 60 }})"></p>
                </li>
                <li>
                    <h6>Total Soal</h6>
                    <p class="text-md font-bold">{{ $this->getRecord()->total_questions }}</p>
                </li>
                <li>
            </ul>
            <div class="table-responsive">
                <table class="table table-scores">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Benar</th>
                            <th>Salah</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->attempts as $attempt)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ is_null($attempt->responses) ? 0 : $attempt->responses->where('points_earned', '>', 0)->count() }}</td>
                            <td>{{ is_null($attempt->responses) ? 0 : $attempt->responses->where('points_earned', 0)->count() }}</td>
                            <td
                                class="{{ ($attempt->result->points_earned >= $this->getRecord()->passing_score) ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}"
                                >
                                {{ is_null($attempt->result) ? 0 : $attempt->result->points_earned }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="grid md:grid-cols-2 gap-4 mt-4">
                <button class="btn-bordered rounded-xl p-3 px-4 font-bold" wire:click="cancel">
                    Kembali
                </button>
                <button class="btn rounded-xl p-3 px-4 text-white font-bold" wire:click="start">
                    Ujian Ulang
                </button>
            </div>
        </div>
        @endif
    </div>

    @script
    <script>
        $wire.on('answer-selected', function([event]) {
            const $options = document.querySelectorAll('.option');
            const $selected = document.getElementById('option.' + event.selectedId);
            
            $options.forEach(option => {
                option.classList.remove('bg-gray-300');
            });
            $selected.classList.add('bg-gray-300');
        });
    </script>
    @endscript
</x-filament-panels::page>
