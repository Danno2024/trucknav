<x-layouts.admin>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Overview of TruckNav platform stats and recent activity.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Total Users</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $stats['active_users'] }} active, {{ $stats['admin_users'] }} admin(s)</div>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Saved Routes</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_routes'] }}</div>
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Road Restrictions</div>
            <div class="text-2xl font-bold text-gray-900">{{ $stats['total_restrictions'] }}</div>
            @if($stats['unverified_restrictions'] > 0)
                <div class="text-xs text-amber-600 mt-1">{{ $stats['unverified_restrictions'] }} unverified</div>
            @endif
        </div>
        <div class="bg-white rounded-lg p-5 shadow-sm border border-gray-200">
            <div class="text-sm text-gray-500 mb-1">Donations</div>
            <div class="text-2xl font-bold text-gray-900">${{ number_format($stats['total_donations'], 2) }}</div>
            <div class="text-xs text-gray-400 mt-1">{{ $stats['total_reviews'] }} reviews @if($stats['avg_rating'])({{ number_format($stats['avg_rating'], 1) }}★)@endif</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentUsers as $user)
                    <a href="{{ route('admin.users.show', $user) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($user->role === 'admin')
                                <span class="px-2 py-0.5 bg-maroon-100 text-maroon-700 text-xs font-medium rounded-full">Admin</span>
                            @endif
                            @if(!$user->is_active)
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full">Inactive</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-4 text-sm text-gray-400 italic">No users yet</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent Restrictions</h2>
                <a href="{{ route('admin.restrictions.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentRestrictions as $restriction)
                    <a href="{{ route('admin.restrictions.show', $restriction) }}" class="flex items-center justify-between px-5 py-3 hover:bg-gray-50 transition">
                        <div class="min-w-0 flex-1">
                            <div class="text-sm font-medium text-gray-900 truncate">{{ $restriction->address }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst($restriction->restriction_type) }} · {{ ucfirst($restriction->severity) }}</div>
                        </div>
                        <div class="flex items-center gap-2 ml-3">
                            @if($restriction->verified)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Verified</span>
                            @else
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">Unverified</span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-4 text-sm text-gray-400 italic">No restrictions reported yet</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900">Recent Routes</h2>
                <a href="{{ route('admin.routes.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentRoutes as $route)
                    <div class="px-5 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ $route->name }}</div>
                        <div class="text-xs text-gray-500 mt-0.5">
                            {{ $route->user?->name ?? 'Unknown' }}
                            @if($route->total_distance_km) · {{ number_format($route->total_distance_km, 1) }} km @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-4 text-sm text-gray-400 italic">No routes saved yet</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
