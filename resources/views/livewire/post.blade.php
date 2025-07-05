<section class="bg-white dark:bg-gray-900">
    @assets
    <style>p{margin-top:1rem;margin-bottom:1rem;font-size:1.125rem;line-height:1.75rem;font-weight:400;}</style>
    @endassets

    <div class="py-8 px-4 mx-auto max-w-screen-lg lg:py-16">
        <div class="flex flex-col justify-center">
            <h1 class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white">{{ $post->title }}</h1>
            <img class="h-auto max-w-full rounded-lg shadow-xl dark:shadow-gray-800" src="{{ asset('storage/' . $post->image) }}" alt="image description">
            <div class="text-gray-500 dark:text-gray-400">
                {!! $post->content !!}
            </div>
        </div>
    </div>
</section>
