<ul
    class="grid md:grid-cols-2 gap-4"
    x-data="{
        formatTime(time) {
                const minutes = time;
                const seconds = (time*60) % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }
    }">
    <li>
        <h6>Kesempatan Ujian Ulang</h6>
        <p class="text-md font-bold">{{ $record->max_attempts }}</p>
    </li>
    <li>
        <h6>Minimal Nilai Kelulusan</h6>
        <p class="text-md font-bold">{{ $record->passing_score }}</p>
    </li>
    <li>
        <h6>Durasi Ujian</h6>
        <p class="text-md font-bold" x-text="formatTime({{ $record->duration_minutes }})"></p>
    </li>
    <li>
        <h6>Total Soal</h6>
        <p class="text-md font-bold">{{ $record->total_questions }}</p>
    </li>
    <li>
</ul>