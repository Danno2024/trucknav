<x-layouts.admin>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Road Restrictions</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $restrictions->total() }} total restrictions</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search address..."
                class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            <select name="type" class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                <option value="">All types</option>
                <option value="low_bridge" {{ request('type') === 'low_bridge' ? 'selected' : '' }}>Low Bridge</option>
                <option value="height_limit" {{ request('type') === 'height_limit' ? 'selected' : '' }}>Height Limit</option>
                <option value="weight_limit" {{ request('type') === 'weight_limit' ? 'selected' : '' }}>Weight Limit</option>
                <option value="width_limit" {{ request('type') === 'width_limit' ? 'selected' : '' }}>Width Limit</option>
                <option value="road_ban" {{ request('type') === 'road_ban' ? 'selected' : '' }}>Road Ban</option>
                <option value="rough_road" {{ request('type') === 'rough_road' ? 'selected' : '' }}>Rough Road</option>
                <option value="other" {{ request('type') === 'other' ? 'selected' : '' }}>Other</option>
            </select>
            <select name="status" class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                <option value="">All status</option>
                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>Unverified</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Filter</button>
            <a href="{{ route('admin.restrictions.create') }}" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-medium hover:bg-maroon-800 transition whitespace-nowrap">Add Restriction</a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Type</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Severity</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Verified</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Reported by</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($restrictions as $restriction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <div class="text-sm font-medium text-gray-900 max-w-xs truncate">{{ $restriction->address }}</div>
                            @if($restriction->description)
                                <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($restriction->description, 50) }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            <span class="text-sm text-gray-600">{{ ucfirst(str_replace('_', ' ', $restriction->restriction_type)) }}</span>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            @php
                                $severityColors = ['low' => 'gray', 'medium' => 'blue', 'high' => 'amber', 'critical' => 'red'];
                                $color = $severityColors[$restriction->severity] ?? 'gray';
                            @endphp
                            <span class="px-2 py-0.5 bg-{{ $color }}-100 text-{{ $color }}-700 text-xs font-medium rounded-full">{{ ucfirst($restriction->severity) }}</span>
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            @if($restriction->verified)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Yes ({{ $restriction->verification_count }})</span>
                            @else
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">No</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden md:table-cell">{{ $restriction->user?->name ?? 'Unknown' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden lg:table-cell">{{ $restriction->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.restrictions.show', $restriction) }}" class="text-sm text-maroon-600 hover:text-maroon-700 font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400 italic">No restrictions found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $restrictions->links() }}
    </div>
</x-layouts.admin>
