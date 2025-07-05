<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        
        @filamentStyles
        @vite('resources/css/app.css')
    </head>
    <body class="fi-body min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white">
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
                {{ $slot }}
            </div>
        </div>
        
        @filamentScripts
        @vite('resources/js/app.js')
    </body>
</html>
