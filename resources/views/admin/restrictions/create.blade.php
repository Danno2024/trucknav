<x-layouts.admin>
    @section('title', 'Add Restriction')

    <div class="mb-6">
        <a href="{{ route('admin.restrictions.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">&larr; Back to Restrictions</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-3xl">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Add New Restriction</h2>

        <form method="POST" action="{{ route('admin.restrictions.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input id="address" type="text" name="address" value="{{ old('address') }}" placeholder="e.g. Montague St, South Melbourne VIC"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>

                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-1">Latitude</label>
                    <input id="latitude" type="number" step="any" name="latitude" value="{{ old('latitude') }}" placeholder="e.g. -37.8256"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>
                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-1">Longitude</label>
                    <input id="longitude" type="number" step="any" name="longitude" value="{{ old('longitude') }}" placeholder="e.g. 144.9584"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>

                <div>
                    <label for="restriction_type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="restriction_type" name="restriction_type"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                        <option value="">Select type...</option>
                        <option value="low_bridge" {{ old('restriction_type') === 'low_bridge' ? 'selected' : '' }}>Low Bridge</option>
                        <option value="height_limit" {{ old('restriction_type') === 'height_limit' ? 'selected' : '' }}>Height Limit</option>
                        <option value="weight_limit" {{ old('restriction_type') === 'weight_limit' ? 'selected' : '' }}>Weight Limit</option>
                        <option value="width_limit" {{ old('restriction_type') === 'width_limit' ? 'selected' : '' }}>Width Limit</option>
                        <option value="road_ban" {{ old('restriction_type') === 'road_ban' ? 'selected' : '' }}>Road Ban</option>
                        <option value="rough_road" {{ old('restriction_type') === 'rough_road' ? 'selected' : '' }}>Rough Road</option>
                        <option value="other" {{ old('restriction_type') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label for="severity" class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                    <select id="severity" name="severity"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                        <option value="medium" {{ old('severity', 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ old('severity') === 'low' ? 'selected' : '' }}>Low</option>
                        <option value="high" {{ old('severity') === 'high' ? 'selected' : '' }}>High</option>
                        <option value="critical" {{ old('severity') === 'critical' ? 'selected' : '' }}>Critical</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm"
                    placeholder="Details drivers need to know...">{{ old('description') }}</textarea>
            </div>

            <p class="text-xs text-gray-400 mb-4">Admin-added restrictions are saved as verified and attributed to your account.</p>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Create Restriction</button>
                <a href="{{ route('admin.restrictions.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
