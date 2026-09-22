<x-layouts.admin>
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Users</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $users->total() }} total users</p>
        </div>
        <form method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..."
                class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            <select name="role" class="border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                <option value="">All roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Routes</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden md:table-cell">Restrictions</th>
                    <th class="px-5 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Joined</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-5 py-4 hidden sm:table-cell">
                            @if($user->role === 'admin')
                                <span class="px-2 py-0.5 bg-maroon-100 text-maroon-700 text-xs font-medium rounded-full">Admin</span>
                            @else
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">User</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 hidden md:table-cell">
                            @if($user->is_active)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Active</span>
                            @else
                                <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs font-medium rounded-full">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-sm text-gray-600 hidden md:table-cell">{{ $user->saved_routes_count }}</td>
                        <td class="px-5 py-4 text-sm text-gray-600 hidden md:table-cell">{{ $user->road_restrictions_count }}</td>
                        <td class="px-5 py-4 text-sm text-gray-500 hidden lg:table-cell">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-4 text-right">
                            <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-maroon-600 hover:text-maroon-700 font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-8 text-center text-sm text-gray-400 italic">No users found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-layouts.admin>
