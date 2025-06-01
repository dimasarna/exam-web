<div class="fi-layout flex min-h-screen w-full flex-row-reverse overflow-x-clip">
    <div class="fi-main-ctn w-screen flex-1 flex-col flex">
        <div class="fi-topbar sticky top-0 z-20 overflow-x-clip">
            <nav class="flex h-16 items-center gap-x-4 bg-white px-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 md:px-6 lg:px-8">
                <div class="me-6 hidden lg:flex">
                    <a href="{{ config('app.url') }}">
                        <div class="fi-logo flex text-xl font-bold leading-5 tracking-tight text-gray-950 dark:text-white">
                            {{ config('app.name') }}
                        </div>
                    </a>
                </div>
            </nav>
        </div>
        <main class="fi-main mx-auto h-full w-full px-4 md:px-6 lg:px-8 max-w-2xl">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-2xl font-semibold my-2">
                    Pengumuman
                </h1>

                <div id="announcement-banner" class="relative" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="overflow-hidden relative h-56 rounded-lg sm:h-64 xl:h-80 2xl:h-96">
                        @foreach ($this->announcements as $announcement)
                        <!-- Item -->
                        @if ($loop->index === 0)
                        <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                            <span class="absolute top-1/2 left-1/2 text-2xl font-semibold text-white -translate-x-1/2 -translate-y-1/2 sm:text-3xl dark:text-gray-800">{{ $announcement->title }}</span>
                            <img src="{{ asset('storage/' . $announcement->image) }}" class="block absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2" alt="Announcement Banner {{ $loop->index + 1 }}">
                        </div>
                        @else
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <span class="absolute top-1/2 left-1/2 text-2xl font-semibold text-white -translate-x-1/2 -translate-y-1/2 sm:text-3xl dark:text-gray-800">{{ $announcement->title }}</span>
                            <img src="{{ asset('storage/' . $announcement->image) }}" class="block absolute top-1/2 left-1/2 w-full -translate-x-1/2 -translate-y-1/2" alt="Announcement Banner {{ $loop->index + 1 }}">
                        </div>
                        @endif
                        @endforeach
                    </div>
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
                </div>

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
    </div>
</div>