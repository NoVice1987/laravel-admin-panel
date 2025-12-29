@extends('admin-panel::frontend.layouts.app')

@section('title', $article->meta_title ?: $article->title)

@section('meta')
    <meta name="description" content="{{ $article->meta_description ?: Str::limit(strip_tags($article->content), 160) }}">
    @if($article->meta_keywords)
        <meta name="keywords" content="{{ $article->meta_keywords }}">
    @endif
    
    <!-- Open Graph -->
    <meta property="og:title" content="{{ $article->title }}">
    <meta property="og:description" content="{{ $article->excerpt ?: Str::limit(strip_tags($article->content), 160) }}">
    @if($article->featured_image)
        <meta property="og:image" content="{{ $article->featured_image }}">
    @endif
    <meta property="og:type" content="article">
@endsection

@section('content')
<article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($article->featured_image)
            <img src="{{ $article->featured_image }}" 
                 alt="{{ $article->title }}"
                 class="w-full h-96 object-cover">
        @endif
        
        <div class="p-8">
            <header class="mb-8">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ $article->title }}
                </h1>
                
                <div class="flex items-center text-gray-600 text-sm">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span>{{ $article->author->name }}</span>
                    </div>
                    <span class="mx-3">•</span>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>{{ $article->published_at->format('F j, Y') }}</span>
                    </div>
                    <span class="mx-3">•</span>
                    <div class="flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span>{{ $article->views_count }} views</span>
                    </div>
                </div>
            </header>
            
            @if($article->excerpt)
                <div class="text-xl text-gray-700 mb-8 font-medium leading-relaxed border-l-4 border-indigo-500 pl-4 py-2 bg-gray-50">
                    {{ $article->excerpt }}
                </div>
            @endif
            
            <div class="prose prose-lg max-w-none">
                {!! $article->content !!}
            </div>
        </div>
    </div>
    
    <!-- Back to News -->
    <div class="mt-8">
        <a href="{{ url('/news') }}" 
           class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to News
        </a>
    </div>
</article>

<!-- Related News -->
@if($relatedNews->count() > 0)
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Related News</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($relatedNews as $related)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    @if($related->featured_image)
                        <img src="{{ $related->featured_image }}" 
                             alt="{{ $related->title }}"
                             class="w-full h-32 object-cover">
                    @else
                        <div class="w-full h-32 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                    @endif
                    
                    <div class="p-4">
                        <h3 class="font-semibold mb-2">
                            <a href="{{ url('/news/' . $related->slug) }}" 
                               class="text-gray-900 hover:text-indigo-600">
                                {{ Str::limit($related->title, 60) }}
                            </a>
                        </h3>
                        <div class="text-xs text-gray-500">
                            {{ $related->published_at->format('M d, Y') }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
