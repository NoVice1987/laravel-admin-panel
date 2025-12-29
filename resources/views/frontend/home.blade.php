@extends('admin-panel::frontend.layouts.app')

@section('title', 'Home')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to {{ config('app.name', 'Our Website') }}</h1>
        <p class="text-xl text-gray-600 mb-6">
            Discover our latest news, updates, and content all in one place.
        </p>
        <div class="flex space-x-4">
            <a href="{{ url('/news') }}" 
               class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700">
                Browse News
            </a>
            <a href="{{ url('/about') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                Learn More
            </a>
        </div>
    </div>

    <!-- Latest News -->
    @if($latestNews->count() > 0)
        <div class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-3xl font-bold text-gray-900">Latest News</h2>
                <a href="{{ url('/news') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    View All →
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestNews as $article)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        @if($article->featured_image)
                            <img src="{{ $article->featured_image }}" 
                                 alt="{{ $article->title }}"
                                 class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-xl font-semibold mb-2">
                                <a href="{{ url('/news/' . $article->slug) }}" 
                                   class="text-gray-900 hover:text-indigo-600">
                                    {{ $article->title }}
                                </a>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-4">
                                {{ Str::limit($article->excerpt ?: strip_tags($article->content), 120) }}
                            </p>
                            
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <span>{{ $article->published_at->format('M d, Y') }}</span>
                                <span>{{ $article->views_count }} views</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
