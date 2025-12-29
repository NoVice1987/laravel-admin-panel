@extends('admin-panel::frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('meta')
    <meta name="description" content="{{ $page->meta_description ?: Str::limit(strip_tags($page->content), 160) }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-8">
        <article class="prose prose-lg max-w-none">
            {!! $page->content !!}
        </article>
    </div>
</div>
@endsection
