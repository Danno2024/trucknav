<x-layouts.admin>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Forum Threads</h1>
        <p class="text-sm text-gray-500 mt-1">Manage all forum threads.</p>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6 p-4">
        <form action="{{ route('admin.forums.threads.index') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label for="search" class="block text-xs font-medium text-gray-500 mb-1">Search</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search threads..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div class="min-w-[180px]">
                <label for="category_id" class="block text-xs font-medium text-gray-500 mb-1">Category</label>
                <select name="category_id" id="category_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                    <option value="">All Categories</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg hover:bg-maroon-800 text-sm font-medium">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium text-gray-600">Title</th>
                        <th class="px-5 py-3 text-left font-medium text-gray-600">Author</th>
                        <th class="px-5 py-3 text-left font-medium text-gray-600">Category</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-600">Replies</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-600">Views</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-600">Status</th>
                        <th class="px-5 py-3 text-right font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($threads as $thread)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3">
                                <div class="font-medium text-gray-900 max-w-xs truncate">{{ $thread->title }}</div>
                                <div class="text-xs text-gray-400">{{ $thread->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-5 py-3 text-gray-700">{{ $thread->user->name }}</td>
                            <td class="px-5 py-3 text-gray-700">{{ $thread->category->name }}</td>
                            <td class="px-5 py-3 text-center text-gray-700">{{ $thread->replies_count }}</td>
                            <td class="px-5 py-3 text-center text-gray-700">{{ $thread->views_count }}</td>
                            <td class="px-5 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($thread->is_pinned)
                                        <span class="px-1.5 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded">Pinned</span>
                                    @endif
                                    @if ($thread->is_locked)
                                        <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded">Locked</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form action="{{ route('admin.forums.threads.pin', $thread->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs {{ $thread->is_pinned ? 'text-amber-600' : 'text-gray-400 hover:text-amber-600' }}" title="{{ $thread->is_pinned ? 'Unpin' : 'Pin' }}">Pin</button>
                                    </form>
                                    <form action="{{ route('admin.forums.threads.lock', $thread->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs {{ $thread->is_locked ? 'text-gray-600' : 'text-gray-400 hover:text-gray-600' }}" title="{{ $thread->is_locked ? 'Unlock' : 'Lock' }}">{{ $thread->is_locked ? 'Unlock' : 'Lock' }}</button>
                                    </form>
                                    <form action="{{ route('admin.forums.threads.destroy', $thread->slug) }}" method="POST" onsubmit="return confirm('Delete this thread and all its posts?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-500 hover:text-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-4 text-sm text-gray-400 italic text-center">No threads found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $threads->withQueryString()->links() }}
    </div>
</x-layouts.admin>
