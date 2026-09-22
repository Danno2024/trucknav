<x-layouts.admin>
    @section('title', 'System Settings')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">System Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Configure your TruckRoute application.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.settings.system.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                <input id="app_name" type="text" name="app_name" value="{{ old('app_name', $settings['app_name']) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
            </div>

            <div class="mb-6">
                <label for="app_url" class="block text-sm font-medium text-gray-700 mb-1">Application URL</label>
                <input id="app_url" type="url" name="app_url" value="{{ old('app_url', $settings['app_url']) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                <p class="mt-1 text-xs text-gray-400">Full URL including protocol (e.g. https://truckroute.com.au)</p>
            </div>

            <div class="border-t border-gray-100 pt-6 mb-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">Maintenance Mode</h2>

                <div class="mb-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ? 'checked' : '' }} class="rounded border-gray-300 text-maroon-600 focus:ring-maroon-500">
                        <span class="text-sm text-gray-700">Enable maintenance mode</span>
                    </label>
                    <p class="mt-1 text-xs text-gray-400">When enabled, only admins can access the site. Regular users will see the maintenance message below.</p>
                </div>

                <div>
                    <label for="maintenance_message" class="block text-sm font-medium text-gray-700 mb-1">Maintenance Message</label>
                    <textarea id="maintenance_message" name="maintenance_message" rows="4"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm"
                        placeholder="We are currently performing scheduled maintenance...">{{ old('maintenance_message', $settings['maintenance_message']) }}</textarea>
                    <p class="mt-1 text-xs text-gray-400">This message is shown to users on the maintenance page and login screen.</p>
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Save Settings</button>
        </form>
    </div>
</x-layouts.admin>
