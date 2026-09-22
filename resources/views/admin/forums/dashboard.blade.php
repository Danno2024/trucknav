<x-layouts.admin>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Forums Overview</h1>
        <p class="text-sm text-gray-500 mt-1">Manage forum categories, threads, and posts.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Categories</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</div>
            <a href="{{ route('admin.forums.categories.index') }}" class="text-xs text-maroon-600 hover:text-maroon-700 mt-1 inline-block">Manage</a>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Threads</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalThreads }}</div>
            <a href="{{ route('admin.forums.threads.index') }}" class="text-xs text-maroon-600 hover:text-maroon-700 mt-1 inline-block">Manage</a>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Posts</div>
            <div class="text-2xl font-bold text-gray-900">{{ $totalPosts }}</div>
            <a href="{{ route('admin.forums.posts.index') }}" class="text-xs text-maroon-600 hover:text-maroon-700 mt-1 inline-block">Manage</a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-900">Recent Threads</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($recentThreads as $thread)
                <div class="flex items-center justify-between px-5 py-3">
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            @if ($thread->is_pinned)
                                <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded">Pinned</span>
                            @endif
                            @if ($thread->is_locked)
                                <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded">Locked</span>
                            @endif
                            <span class="text-sm font-medium text-gray-900 truncate">{{ $thread->title }}</span>
                        </div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            by {{ $thread->user->name }} in {{ $thread->category->name }} · {{ $thread->replies_count }} replies · {{ $thread->views_count }} views
                        </div>
                    </div>
                    <div class="flex items-center gap-2 ml-4">
                        <form action="{{ route('admin.forums.threads.pin', $thread->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs {{ $thread->is_pinned ? 'text-amber-600 hover:text-amber-700' : 'text-gray-400 hover:text-amber-600' }}" title="{{ $thread->is_pinned ? 'Unpin' : 'Pin' }}">
                                <svg class="w-4 h-4" fill="{{ $thread->is_pinned ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.forums.threads.lock', $thread->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs {{ $thread->is_locked ? 'text-gray-600 hover:text-gray-700' : 'text-gray-400 hover:text-gray-600' }}" title="{{ $thread->is_locked ? 'Unlock' : 'Lock' }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $thread->is_locked ? 'M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z' : 'M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Zm9.75-10.5V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v3.75a2.25 2.25 0 0 0 2.25 2.25h8.25a2.25 2.25 0 0 0 2.25-2.25Z' }}"/></svg>
                            </button>
                        </form>
                        <form action="{{ route('admin.forums.threads.destroy', $thread->slug) }}" method="POST" onsubmit="return confirm('Delete this thread and all its posts?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-gray-400 hover:text-red-600" title="Delete">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-5 py-4 text-sm text-gray-400 italic">No threads yet.</div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>
