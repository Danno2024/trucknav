<x-layouts.admin>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Forum Posts</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all forum posts.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <form action="{{ route('admin.forums.posts.index') }}" method="GET" class="flex items-end gap-4">
            <div class="flex-1">
                <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search posts</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search content..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg hover:bg-maroon-800 text-sm font-medium">Search</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="divide-y divide-gray-100">
            @forelse ($posts as $post)
                <div class="px-5 py-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-gray-900 text-sm">{{ $post->user->name }}</span>
                                <span class="text-xs text-gray-400">in</span>
                                <a href="{{ route('forums.thread', $post->thread->slug) }}" class="text-xs text-maroon-600 hover:text-maroon-700 font-medium">{{ Str::limit($post->thread->title, 40) }}</a>
                                <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                @if ($post->is_edited)
                                    <span class="text-xs text-gray-400">(edited)</span>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 line-clamp-2">{!! Str::limit(strip_tags($post->content), 200) !!}</div>
                        </div>
                        <form action="{{ route('admin.forums.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')" class="ml-4 shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-500 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-5 py-4 text-sm text-gray-400 italic text-center">No posts found.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $posts->withQueryString()->links() }}
    </div>
</x-layouts.admin>
