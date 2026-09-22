<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('forums.index') }}" class="text-maroon-700 hover:text-maroon-800">Forums</a>
            <span class="text-gray-400 mx-1">/</span>
            {{ $category->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                @if ($category->description)
                    <p class="text-gray-600">{{ $category->description }}</p>
                @else
                    <div></div>
                @endif
                <a href="{{ route('forums.create') }}?category={{ $category->id }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 text-white rounded-lg hover:bg-maroon-800 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Thread
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 divide-y divide-gray-200">
                @forelse ($threads as $thread)
                    <a href="{{ route('forums.thread', $thread->slug) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                @if ($thread->is_pinned)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Pinned</span>
                                @endif
                                @if ($thread->is_locked)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Locked</span>
                                @endif
                                <h3 class="font-medium text-gray-900 truncate">{{ $thread->title }}</h3>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-gray-500">
                                <span>by {{ $thread->user->name }}</span>
                                <span>{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</span>
                                <span>{{ $thread->views_count }} {{ Str::plural('view', $thread->views_count) }}</span>
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 ml-4 shrink-0">
                            {{ $thread->last_post_at?->diffForHumans() ?? '' }}
                        </div>
                    </a>
                @empty
                    <div class="p-12 text-center">
                        <p class="text-gray-500">No threads yet. Be the first to start one!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $threads->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
