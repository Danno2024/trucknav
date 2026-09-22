<x-layouts.admin>
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">&larr; Back to Users</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">User Details</h2>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Name</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Email</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Joined</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $user->created_at->format('d M Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Email Verified</dt>
                        <dd class="text-sm mt-1">{{ $user->email_verified_at ? $user->email_verified_at->format('d M Y') : 'Not verified' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Saved Routes ({{ $user->savedRoutes->count() }})</h2>
                @forelse($user->savedRoutes as $route)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $route->name }}</div>
                            <div class="text-xs text-gray-500">{{ $route->origin_address }} → {{ $route->destination_address }}</div>
                        </div>
                        <span class="text-xs text-gray-400">{{ $route->created_at->diffForHumans() }}</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">No saved routes</p>
                @endforelse
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Road Restrictions ({{ $user->roadRestrictions->count() }})</h2>
                @forelse($user->roadRestrictions as $restriction)
                    <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $restriction->address }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst($restriction->restriction_type) }} · {{ ucfirst($restriction->severity) }}</div>
                        </div>
                        @if($restriction->verified)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs font-medium rounded-full">Verified</span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">Unverified</span>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">No restrictions reported</p>
                @endforelse
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Reviews ({{ $user->reviews->count() }})</h2>
                @forelse($user->reviews as $review)
                    <div class="py-2 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                        <div class="flex items-center gap-2">
                            <span class="text-yellow-500">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                            <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        @if($review->comment)
                            <p class="text-sm text-gray-600 mt-1">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-sm text-gray-400 italic">No reviews</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Edit User</h2>
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="role" name="role" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-maroon-600 focus:ring-maroon-500">
                            <span class="text-sm text-gray-700">Active account</span>
                        </label>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Update User</button>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Danger Zone</h2>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user and all their data? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-semibold hover:bg-red-700 transition" {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                        Delete User
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
