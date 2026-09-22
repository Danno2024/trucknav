<x-layouts.admin>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Donations</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $count }} total donations · ${{ number_format($totalCompleted, 2) }} completed · ${{ number_format($totalPending, 2) }} pending</p>
        </div>
        <form method="GET" class="flex gap-2">
            <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                <option value="">All status</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">User</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Transaction ID</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <div class="text-sm font-semibold text-gray-900">${{ number_format($donation->amount, 2) }} {{ $donation->currency }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 hidden sm:table-cell">{{ $donation->user?->name ?? 'Anonymous' }}</td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            @php
                                $statusColors = ['completed' => 'green', 'pending' => 'amber', 'failed' => 'red'];
                                $color = $statusColors[$donation->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-0.5 bg-{{ $color }}-100 text-{{ $color }}-700 text-xs font-medium rounded-full capitalize">{{ $donation->status }}</span>
                        </td>
                        <td class="px-5 py-4 text-xs text-gray-500 font-mono hidden md:table-cell">{{ $donation->paypal_transaction_id ?: '—' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden lg:table-cell">{{ $donation->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-8 text-center text-sm text-gray-400 italic">No donations yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $donations->links() }}
    </div>
</x-layouts.admin>
