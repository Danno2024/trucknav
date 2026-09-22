<x-layouts.admin>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Forum Categories</h1>
            <p class="text-sm text-gray-500 mt-1">Manage forum categories.</p>
        </div>
        <a href="{{ route('admin.forums.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 text-white rounded-lg hover:bg-maroon-800 transition-colors text-sm font-medium">
            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Add Category
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium text-gray-600">Name</th>
                        <th class="px-5 py-3 text-left font-medium text-gray-600">Description</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-600">Threads</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-600">Order</th>
                        <th class="px-5 py-3 text-center font-medium text-gray-600">Active</th>
                        <th class="px-5 py-3 text-right font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-3 font-medium text-gray-900">{{ $category->name }}</td>
                            <td class="px-5 py-3 text-gray-500 max-w-xs truncate">{{ $category->description }}</td>
                            <td class="px-5 py-3 text-center text-gray-700">{{ $category->threads_count }}</td>
                            <td class="px-5 py-3 text-center text-gray-700">{{ $category->sort_order }}</td>
                            <td class="px-5 py-3 text-center">
                                @if ($category->is_active)
                                    <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Active</span>
                                @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs font-medium rounded-full">Inactive</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.forums.categories.edit', $category->slug) }}" class="text-maroon-600 hover:text-maroon-700 text-xs font-medium">Edit</a>
                                    <form action="{{ route('admin.forums.categories.destroy', $category->slug) }}" method="POST" onsubmit="return confirm('Delete this category? All threads will be deleted.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600 text-xs font-medium">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-4 text-sm text-gray-400 italic text-center">No categories yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
