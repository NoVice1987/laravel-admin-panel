<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    @yield('meta')

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Navigation -->
    @php
        $mainMenu = \StatisticLv\AdminPanel\Models\Menu::where('location', 'main')
            ->where('is_active', true)
            ->with('items.children')
            ->first();
    @endphp

    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-gray-900">
                            {{ config('app.name', 'Laravel') }}
                        </a>
                    </div>

                    @if ($mainMenu && $mainMenu->items->count() > 0)
                        <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                            @foreach ($mainMenu->items as $item)
                                <a href="{{ $item->getDisplayUrl() }}" target="{{ $item->target }}"
                                    class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium {{ request()->url() == $item->getDisplayUrl() ? 'border-indigo-500 text-gray-900' : '' }}">
                                    {{ $item->title }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center sm:hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false"
                        class="absolute top-16 left-0 right-0 bg-white shadow-lg z-50" style="display: none;">
                        @if ($mainMenu && $mainMenu->items->count() > 0)
                            <div class="pt-2 pb-3 space-y-1">
                                @foreach ($mainMenu->items as $item)
                                    <a href="{{ $item->getDisplayUrl() }}" target="{{ $item->target }}"
                                        class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium {{ request()->url() == $item->getDisplayUrl() ? 'bg-indigo-50 border-indigo-500 text-indigo-700' : 'border-transparent text-gray-500 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-700' }}">
                                        {{ $item->title }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main class="py-8 h-100">
        @yield('content')
    </main>

    <!-- Footer -->
    @php
        $footerMenu = \StatisticLv\AdminPanel\Models\Menu::where('location', 'footer')
            ->where('is_active', true)
            ->with('items')
            ->first();
    @endphp

    <footer class="bg-gray-800 text-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- About Section -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">{{ config('app.name', 'Laravel') }}</h3>
                    <p class="text-gray-300 text-sm">
                        A modern website built with Laravel Admin Panel.
                    </p>
                </div>

                <!-- Quick Links -->
                @if ($footerMenu && $footerMenu->items->count() > 0)
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            @foreach ($footerMenu->items as $item)
                                <li>
                                    <a href="{{ $item->getDisplayUrl() }}" target="{{ $item->target }}"
                                        class="text-gray-300 hover:text-white text-sm">
                                        {{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Latest News -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Latest News</h3>
                    @php
                        $latestFooterNews = \StatisticLv\AdminPanel\Models\News::published()
                            ->orderBy('published_at', 'desc')
                            ->take(3)
                            ->get();
                    @endphp
                    <ul class="space-y-2">
                        @foreach ($latestFooterNews as $news)
                            <li>
                                <a href="{{ url('/news/' . $news->slug) }}"
                                    class="text-gray-300 hover:text-white text-sm">
                                    {{ Str::limit($news->title, 50) }}
                                </a>
                                <div class="text-xs text-gray-400">
                                    {{ $news->published_at->format('M d, Y') }}
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
                <p>&copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>

</html>
