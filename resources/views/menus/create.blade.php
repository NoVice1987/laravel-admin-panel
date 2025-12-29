@extends('admin-panel::layouts.app')

@section('title', 'Create Menu')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Create Menu</h1>
        <a href="{{ route('admin.menus.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Back to List
        </a>
    </div>

    <div class="bg-white shadow sm:rounded-lg">
        <form action="{{ route('admin.menus.store') }}" method="POST" class="px-6 py-5 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Menu Name *</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}"
                       required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug (optional)</label>
                <input type="text" 
                       name="slug" 
                       id="slug" 
                       value="{{ old('slug') }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('slug') border-red-500 @enderror">
                <p class="mt-1 text-sm text-gray-500">Leave blank to auto-generate from name</p>
                @error('slug')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                <select name="location" 
                        id="location" 
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm @error('location') border-red-500 @enderror">
                    <option value="main" {{ old('location') === 'main' ? 'selected' : '' }}>Main Navigation</option>
                    <option value="footer" {{ old('location') === 'footer' ? 'selected' : '' }}>Footer</option>
                    <option value="sidebar" {{ old('location') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                </select>
                @error('location')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active" 
                       value="1"
                       {{ old('is_active', true) ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.menus.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Create Menu
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
