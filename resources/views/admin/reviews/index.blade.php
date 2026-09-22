<x-layouts.admin>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Reviews</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $reviews->total() }} total reviews @if($avgRating) · Average: {{ number_format($avgRating, 1) }}★ @endif</p>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="rating" class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                <option value="">All ratings</option>
                @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }}★</option>
                @endfor
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">User</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Platform</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($reviews as $review)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <span class="text-yellow-500 text-lg">{{ str_repeat('★', $review->rating ?? 0) }}{{ str_repeat('☆', 5 - ($review->rating ?? 0)) }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <div class="text-sm text-gray-900 max-w-sm">{{ Str::limit($review->comment, 80) ?: '—' }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 hidden sm:table-cell">{{ $review->user?->name ?? 'Unknown' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden md:table-cell capitalize">{{ $review->platform }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden lg:table-cell">{{ $review->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-right">
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400 italic">No reviews yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
</x-layouts.admin>
