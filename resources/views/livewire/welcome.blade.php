<main class="fi-main mx-auto h-full w-full px-4 md:px-6 lg:px-8 max-w-2xl">
    <div class="max-w-2xl mx-auto">
        @if ($this->announcements->isNotEmpty())
        <h1 class="text-2xl font-semibold my-2">
            Pengumuman
        </h1>

        <div id="announcement-banner"
            class="relative"
            {!! $this->announcements->count() > 1 ? 'data-carousel="slide"' : '' !!}
        >
            <!-- Carousel wrapper -->
            <div class="overflow-hidden relative h-56 rounded-lg sm:h-64 xl:h-80 2xl:h-96">
            @foreach ($this->announcements as $announcement)
                <!-- Item -->
                @if ($loop->first)
                <div class="duration-700 ease-in-out" data-carousel-item="active">
                    <span class="absolute top-1/2 left-1/2 text-2xl font-semibold text-white -translate-x-1/2 -translate-y-1/2 sm:text-3xl dark:text-gray-800">{{ $announcement->title }}</span>
                    <a href="/posts/{{ $announcement->id }}">
                        <img src="{{ asset('storage/' . $announcement->image) }}" class="block absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2" alt="Announcement Banner {{ $loop->iteration }}">
                    </a>
                </div>
                @else
                <div class="hidden duration-700 ease-in-out" data-carousel-item>
                    <span class="absolute top-1/2 left-1/2 text-2xl font-semibold text-white -translate-x-1/2 -translate-y-1/2 sm:text-3xl dark:text-gray-800">{{ $announcement->title }}</span>
                    <a href="/posts/{{ $announcement->id }}">
                        <img src="{{ asset('storage/' . $announcement->image) }}" class="block absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2" alt="Announcement Banner {{ $loop->iteration }}">
                    </a>
                </div>
                @endif
            @endforeach
            </div>
            @if ($this->announcements->count() > 1)
            <!-- Slider controls -->
            <button type="button" class="flex absolute top-0 left-0 z-30 justify-center items-center px-4 h-full cursor-pointer group focus:outline-none" data-carousel-prev>
                <span class="inline-flex justify-center items-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-5 h-5 text-white sm:w-6 sm:h-6 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="hidden">Previous</span>
                </span>
            </button>
            <button type="button" class="flex absolute top-0 right-0 z-30 justify-center items-center px-4 h-full cursor-pointer group focus:outline-none" data-carousel-next>
                <span class="inline-flex justify-center items-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                    <svg class="w-5 h-5 text-white sm:w-6 sm:h-6 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    <span class="hidden">Next</span>
                </span>
            </button>
            @endif
        </div>
        @endif

        <p class="mt-5 w-full">
            <x-filament::button
                class="w-full"
                size="xl"
                href="{{ config('app.url') . '/app' }}"
                tag="a">
                LOG IN
            </x-filament::button>
        </p>
        <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
    </div>
</main>