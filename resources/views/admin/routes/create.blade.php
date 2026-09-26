<x-layouts.admin>
    @section('title', 'Add Route')

    <div class="mb-6">
        <a href="{{ route('admin.routes.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">&larr; Back to Routes</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-3xl">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Add New Route</h2>

        <form method="POST" action="{{ route('admin.routes.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Owner</label>
                    <select id="user_id" name="user_id"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                        <option value="">Select a user...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Route Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>

                <div>
                    <label for="origin_address" class="block text-sm font-medium text-gray-700 mb-1">Origin Address</label>
                    <input id="origin_address" type="text" name="origin_address" value="{{ old('origin_address') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>
                <div>
                    <label for="destination_address" class="block text-sm font-medium text-gray-700 mb-1">Destination Address</label>
                    <input id="destination_address" type="text" name="destination_address" value="{{ old('destination_address') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>

                <div>
                    <label for="origin_lat" class="block text-sm font-medium text-gray-700 mb-1">Origin Lat</label>
                    <input id="origin_lat" type="number" step="any" name="origin_lat" value="{{ old('origin_lat') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>
                <div>
                    <label for="origin_lng" class="block text-sm font-medium text-gray-700 mb-1">Origin Lng</label>
                    <input id="origin_lng" type="number" step="any" name="origin_lng" value="{{ old('origin_lng') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>

                <div>
                    <label for="destination_lat" class="block text-sm font-medium text-gray-700 mb-1">Destination Lat</label>
                    <input id="destination_lat" type="number" step="any" name="destination_lat" value="{{ old('destination_lat') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>
                <div>
                    <label for="destination_lng" class="block text-sm font-medium text-gray-700 mb-1">Destination Lng</label>
                    <input id="destination_lng" type="number" step="any" name="destination_lng" value="{{ old('destination_lng') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                </div>

                <div>
                    <label for="total_distance_km" class="block text-sm font-medium text-gray-700 mb-1">Distance (km) <span class="text-gray-400 font-normal">— optional</span></label>
                    <input id="total_distance_km" type="number" step="any" min="0" name="total_distance_km" value="{{ old('total_distance_km') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
                <div>
                    <label for="total_duration_minutes" class="block text-sm font-medium text-gray-700 mb-1">Duration (min) <span class="text-gray-400 font-normal">— optional</span></label>
                    <input id="total_duration_minutes" type="number" min="0" name="total_duration_minutes" value="{{ old('total_duration_minutes') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
            </div>

            <h3 class="text-sm font-semibold text-gray-900 mb-3 border-t border-gray-100 pt-4">Vehicle Profile</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
                    <select id="vehicle_type" name="vehicle_type"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        <option value="truck" {{ old('vehicle_type', 'truck') === 'truck' ? 'selected' : '' }}>Truck</option>
                        <option value="bus" {{ old('vehicle_type') === 'bus' ? 'selected' : '' }}>Bus</option>
                        <option value="car" {{ old('vehicle_type') === 'car' ? 'selected' : '' }}>Car</option>
                    </select>
                </div>
                <div>
                    <label for="vehicle_weight_kg" class="block text-sm font-medium text-gray-700 mb-1">Weight (kg)</label>
                    <input id="vehicle_weight_kg" type="number" name="vehicle_weight_kg" value="{{ old('vehicle_weight_kg') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" placeholder="e.g. 8000">
                </div>
                <div>
                    <label for="vehicle_height_m" class="block text-sm font-medium text-gray-700 mb-1">Height (m)</label>
                    <input id="vehicle_height_m" type="number" step="0.1" name="vehicle_height_m" value="{{ old('vehicle_height_m') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" placeholder="e.g. 4.6">
                </div>
                <div>
                    <label for="vehicle_width_m" class="block text-sm font-medium text-gray-700 mb-1">Width (m)</label>
                    <input id="vehicle_width_m" type="number" step="0.1" name="vehicle_width_m" value="{{ old('vehicle_width_m') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" placeholder="e.g. 2.5">
                </div>
                <div>
                    <label for="vehicle_length_m" class="block text-sm font-medium text-gray-700 mb-1">Length (m)</label>
                    <input id="vehicle_length_m" type="number" step="0.1" name="vehicle_length_m" value="{{ old('vehicle_length_m') }}"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" placeholder="e.g. 16.5">
                </div>
            </div>

            <div class="mb-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm"
                    placeholder="Optional notes about this route...">{{ old('notes') }}</textarea>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center gap-3">
                <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Create Route</button>
                <a href="{{ route('admin.routes.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">Cancel</a>
            </div>
        </form>
    </div>
</x-layouts.admin>
