@extends('admin-panel::frontend.layouts.app')

@section('title', 'News')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">News</h1>
    
    <div class="space-y-8">
        @forelse($news as $article)
            <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="md:flex">
                    @if($article->featured_image)
                        <div class="md:w-1/3">
                            <img src="{{ $article->featured_image }}" 
                                 alt="{{ $article->title }}"
                                 class="w-full h-64 object-cover">
                        </div>
                    @endif
                    
                    <div class="p-6 {{ $article->featured_image ? 'md:w-2/3' : 'w-full' }}">
                        <h2 class="text-2xl font-bold mb-3">
                            <a href="{{ url('/news/' . $article->slug) }}" 
                               class="text-gray-900 hover:text-indigo-600">
                                {{ $article->title }}
                            </a>
                        </h2>
                        
                        @if($article->excerpt)
                            <p class="text-gray-700 mb-4">{{ $article->excerpt }}</p>
                        @else
                            <p class="text-gray-700 mb-4">
                                {{ Str::limit(strip_tags($article->content), 300) }}
                            </p>
                        @endif
                        
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500">
                                <span>{{ $article->published_at->format('F j, Y') }}</span>
                                <span class="mx-2">•</span>
                                <span>By {{ $article->author->name }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ $article->views_count }} views</span>
                            </div>
                            
                            <a href="{{ url('/news/' . $article->slug) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium">
                                Read More →
                            </a>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No news articles</h3>
                <p class="mt-1 text-sm text-gray-500">Check back later for updates.</p>
            </div>
        @endforelse
    </div>
    
    @if($news->hasPages())
        <div class="mt-8">
            {{ $news->links() }}
        </div>
    @endif
</div>
@endsection
