<x-layouts.admin>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Saved Routes</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $routes->total() }} total routes</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search routes..."
                class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Search</button>
            <a href="{{ route('admin.routes.create') }}" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-medium hover:bg-maroon-800 transition whitespace-nowrap">Add Route</a>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">Route</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">User</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Distance</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Vehicle</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Date</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($routes as $route)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $route->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ Str::limit($route->origin_address, 30) }} → {{ Str::limit($route->destination_address, 30) }}</div>
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 hidden sm:table-cell">{{ $route->user?->name ?? 'Unknown' }}</td>
                        <td class="px-5 py-4 text-sm text-gray-600 hidden md:table-cell">
                            @if($route->total_distance_km)
                                {{ number_format($route->total_distance_km, 1) }} km
                            @endif
                            @if($route->total_duration_minutes)
                                · {{ $route->total_duration_minutes }} min
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden md:table-cell">
                            {{ ucfirst($route->vehicle_type) }}
                            @if($route->vehicle_height_m) H:{{ $route->vehicle_height_m }}m @endif
                            @if($route->vehicle_width_m) W:{{ $route->vehicle_width_m }}m @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden lg:table-cell">{{ $route->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.routes.edit', $route) }}" class="text-sm text-maroon-600 hover:text-maroon-700 font-medium mr-3">Edit</a>
                            <form method="POST" action="{{ route('admin.routes.destroy', $route) }}" onsubmit="return confirm('Delete this route?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-medium">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-8 text-center text-sm text-gray-400 italic">No routes found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $routes->links() }}
    </div>
</x-layouts.admin>
