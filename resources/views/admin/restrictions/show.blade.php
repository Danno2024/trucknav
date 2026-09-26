<x-layouts.admin>
    <div class="mb-6">
        <a href="{{ route('admin.restrictions.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">&larr; Back to Restrictions</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-start justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Restriction Details</h2>
                    <div class="flex gap-2">
                        @if(!$restriction->verified)
                            <form method="POST" action="{{ route('admin.restrictions.verify', $restriction) }}">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition">Verify</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.restrictions.destroy', $restriction) }}" onsubmit="return confirm('Delete this restriction?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">Delete</button>
                        </form>
                    </div>
                </div>
                <dl class="grid grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Address</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $restriction->address }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Type</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ ucfirst(str_replace('_', ' ', $restriction->restriction_type)) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Severity</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($restriction->severity) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Coordinates</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $restriction->latitude }}, {{ $restriction->longitude }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Verified</dt>
                        <dd class="text-sm mt-1">{{ $restriction->verified ? 'Yes (' . $restriction->verification_count . ' verifications)' : 'No' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Reported by</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $restriction->user?->name ?? 'Unknown' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Date</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ $restriction->created_at->format('d M Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 uppercase">Status</dt>
                        <dd class="text-sm text-gray-900 mt-1">{{ ucfirst($restriction->status) }}</dd>
                    </div>
                </dl>
                @if($restriction->description)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <dt class="text-xs font-medium text-gray-500 uppercase mb-1">Description</dt>
                        <dd class="text-sm text-gray-700">{{ $restriction->description }}</dd>
                    </div>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Edit Restriction</h2>
                <form method="POST" action="{{ route('admin.restrictions.update', $restriction) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input id="address" type="text" name="address" value="{{ old('address', $restriction->address) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <x-map-picker mode="single" id="restriction-map"
                            latName="latitude" lngName="longitude"
                            :lat="old('latitude', $restriction->latitude)" :lng="old('longitude', $restriction->longitude)" />
                    </div>
                    <div class="mb-3">
                        <label for="restriction_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="restriction_type" name="restriction_type" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                            <option value="low_bridge" {{ $restriction->restriction_type === 'low_bridge' ? 'selected' : '' }}>Low Bridge</option>
                            <option value="height_limit" {{ $restriction->restriction_type === 'height_limit' ? 'selected' : '' }}>Height Limit</option>
                            <option value="weight_limit" {{ $restriction->restriction_type === 'weight_limit' ? 'selected' : '' }}>Weight Limit</option>
                            <option value="width_limit" {{ $restriction->restriction_type === 'width_limit' ? 'selected' : '' }}>Width Limit</option>
                            <option value="road_ban" {{ $restriction->restriction_type === 'road_ban' ? 'selected' : '' }}>Road Ban</option>
                            <option value="rough_road" {{ $restriction->restriction_type === 'rough_road' ? 'selected' : '' }}>Rough Road</option>
                            <option value="other" {{ $restriction->restriction_type === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="severity" class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                        <select id="severity" name="severity" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                            <option value="low" {{ $restriction->severity === 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ $restriction->severity === 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ $restriction->severity === 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ $restriction->severity === 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">
                            <option value="active" {{ $restriction->status === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ $restriction->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm text-sm">{{ $restriction->description }}</textarea>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Update Restriction</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>
