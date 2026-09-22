<x-layouts.admin>
    @section('title', 'PayPal Settings')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">PayPal Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Configure PayPal integration for donations.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.settings.paypal.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="business_email" class="block text-sm font-medium text-gray-700 mb-1">PayPal Business Email</label>
                <input id="business_email" type="email" name="business_email" value="{{ old('business_email', $settings['business_email']) }}"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm" required>
                <p class="mt-1 text-xs text-gray-400">The PayPal email address that receives donations.</p>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                    <div class="text-sm text-amber-700">
                        <p class="font-medium">Production vs Sandbox</p>
                        <p class="mt-1">This sets the business email in your <code class="bg-amber-100 px-1 rounded">.env</code> file. For production, use your live PayPal email. For testing, use a Sandbox email.</p>
                    </div>
                </div>
            </div>

            <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Save PayPal Settings</button>
        </form>
    </div>
</x-layouts.admin>
