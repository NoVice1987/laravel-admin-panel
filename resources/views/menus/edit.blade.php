@extends('admin-panel::layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Edit Menu: {{ $menu->name }}</h1>
        <a href="{{ route('admin.menus.index') }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
            Back to List
        </a>
    </div>

    <!-- Menu Settings -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Menu Settings</h3>
        </div>
        <form action="{{ route('admin.menus.update', $menu) }}" method="POST" class="px-6 py-5 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Menu Name *</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $menu->name) }}"
                       required
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="slug" class="block text-sm font-medium text-gray-700">Slug</label>
                <input type="text" 
                       name="slug" 
                       id="slug" 
                       value="{{ old('slug', $menu->slug) }}"
                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>

            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location *</label>
                <select name="location" 
                        id="location" 
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="main" {{ old('location', $menu->location) === 'main' ? 'selected' : '' }}>Main Navigation</option>
                    <option value="footer" {{ old('location', $menu->location) === 'footer' ? 'selected' : '' }}>Footer</option>
                    <option value="sidebar" {{ old('location', $menu->location) === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                </select>
            </div>

            <div class="flex items-center">
                <input type="checkbox" 
                       name="is_active" 
                       id="is_active" 
                       value="1"
                       {{ old('is_active', $menu->is_active) ? 'checked' : '' }}
                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Active
                </label>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                    Update Menu
                </button>
            </div>
        </form>
    </div>

    <!-- Menu Items -->
    <div class="bg-white shadow sm:rounded-lg">
        <div class="px-6 py-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Menu Items</h3>
        </div>
        
        <div class="px-6 py-5">
            @if($menu->items->count() > 0)
                <ul class="space-y-3">
                    @foreach($menu->items as $item)
                        <li class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $item->title }}</h4>
                                    <p class="text-sm text-gray-500">
                                        @if($item->getRawUrl())
                                            URL: {{ $item->getRawUrl() }}
                                        @elseif($item->getRawRoute())
                                            Route: {{ $item->getRawRoute() }}
                                        @endif
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs text-gray-500">Order: {{ $item->order }}</span>
                                    <form action="{{ route('admin.menus.items.destroy', [$menu, $item]) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm">Delete</button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($item->children->count() > 0)
                                <ul class="mt-3 ml-6 space-y-2">
                                    @foreach($item->children as $child)
                                        <li class="border-l-2 border-gray-200 pl-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h5 class="text-sm text-gray-700">{{ $child->title }}</h5>
                                                    <p class="text-xs text-gray-500">
                                                        @if($child->getRawUrl())
                                                            {{ $child->getRawUrl() }}
                                                        @elseif($child->getRawRoute())
                                                            {{ $child->getRawRoute() }}
                                                        @endif
                                                    </p>
                                                </div>
                                                <form action="{{ route('admin.menus.items.destroy', [$menu, $child]) }}" method="POST" onsubmit="return confirm('Delete this item?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 text-xs">Delete</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-500 text-center py-8">No menu items yet. Add your first item below.</p>
            @endif
        </div>

        <!-- Add New Item Form -->
        <div class="px-6 py-5 bg-gray-50 border-t border-gray-200">
            <h4 class="text-sm font-medium text-gray-900 mb-4">Add Menu Item</h4>
            <form action="{{ route('admin.menus.items.store', $menu) }}" method="POST" class="space-y-4">
                @csrf
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title *</label>
                        <input type="text" name="title" id="title" required
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Item</label>
                        <select name="parent_id" id="parent_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">None (Top Level)</option>
                            @foreach($menu->items as $item)
                                <option value="{{ $item->id }}">{{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                        <input type="text" name="url" id="url" placeholder="https://example.com or /about"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Full URL or relative path</p>
                    </div>

                    <div>
                        <label for="route" class="block text-sm font-medium text-gray-700">Route Name</label>
                        <input type="text" name="route" id="route" placeholder="home or news.show"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Laravel route name (optional, takes priority over URL)</p>
                    </div>

                    <div>
                        <label for="target" class="block text-sm font-medium text-gray-700">Target *</label>
                        <select name="target" id="target" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="_self">Same Window (_self)</option>
                            <option value="_blank">New Window (_blank)</option>
                        </select>
                    </div>

                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
                        <input type="number" name="order" id="order" value="0"
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                    </div>
                </div>

                <div>
                    <label for="css_class" class="block text-sm font-medium text-gray-700">CSS Classes</label>
                    <input type="text" name="css_class" id="css_class" placeholder="nav-item custom-class"
                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <p class="mt-1 text-xs text-gray-500">Space-separated CSS class names (optional)</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
