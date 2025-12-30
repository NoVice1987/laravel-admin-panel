@extends('admin-panel::layouts.app')

@section('title', 'Edit Settings')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Edit Settings</h1>
        <a href="{{ route('admin.settings.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Settings
        </a>
    </div>

    @if(session('success'))
        <div class="rounded-md bg-green-50 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="space-y-6">
            @if($settings->count() > 0)
                @foreach($settings as $group => $groupSettings)
                    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">{{ ucfirst($group) }} Settings</h3>
                        </div>
                        <div class="px-6 py-4 space-y-6">
                            @foreach($groupSettings as $setting)
                                <div>
                                    <label for="{{ $setting->key }}" class="block text-sm font-medium text-gray-700">
                                        {{ $setting->title }}
                                    </label>
                                    @if($setting->description)
                                        <p class="mt-1 text-sm text-gray-500">{{ $setting->description }}</p>
                                    @endif
                                    
                                    <div class="mt-2">
                                        @if($setting->type === 'text')
                                            <input type="text" 
                                                   id="{{ $setting->key }}" 
                                                   name="{{ $setting->key }}" 
                                                   value="{{ old($setting->key, $setting->value) }}"
                                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @elseif($setting->type === 'textarea')
                                            <textarea id="{{ $setting->key }}" 
                                                      name="{{ $setting->key }}" 
                                                      rows="4"
                                                      class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">{{ old($setting->key, $setting->value) }}</textarea>
                                        @elseif($setting->type === 'number')
                                            <input type="number" 
                                                   id="{{ $setting->key }}" 
                                                   name="{{ $setting->key }}" 
                                                   value="{{ old($setting->key, $setting->value) }}"
                                                   class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        @elseif($setting->type === 'boolean')
                                            <div class="flex items-center">
                                                <input type="checkbox" 
                                                       id="{{ $setting->key }}" 
                                                       name="{{ $setting->key }}" 
                                                       value="1"
                                                       {{ old($setting->key, $setting->value) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                                <label for="{{ $setting->key }}" class="ml-2 block text-sm text-gray-900">
                                                    Enabled
                                                </label>
                                            </div>
                                        @elseif($setting->type === 'select')
                                            <select id="{{ $setting->key }}" 
                                                    name="{{ $setting->key }}"
                                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                                @if($setting->options)
                                                    @foreach($setting->options as $value => $label)
                                                        <option value="{{ $value }}" {{ old($setting->key, $setting->value) == $value ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-6 py-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No settings found</h3>
                        <p class="mt-1 text-sm text-gray-500">Please create some settings first.</p>
                    </div>
                </div>
            @endif
        </div>

        <div class="pt-6">
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection