<x-layouts.admin>
    @section('title', 'Email Settings')

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Email Settings</h1>
        <p class="text-sm text-gray-500 mt-1">Configure SMTP sending and control which emails the site sends.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl mb-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Mail Server</h2>

        <form method="POST" action="{{ route('admin.settings.email.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="mail_mailer" class="block text-sm font-medium text-gray-700 mb-1">Mail Method</label>
                <select id="mail_mailer" name="mail_mailer"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                    <option value="" {{ $settings['mail_mailer'] === '' ? 'selected' : '' }}>Use .env default ({{ env('MAIL_MAILER', 'log') }})</option>
                    <option value="smtp" {{ $settings['mail_mailer'] === 'smtp' ? 'selected' : '' }}>SMTP</option>
                    <option value="log" {{ $settings['mail_mailer'] === 'log' ? 'selected' : '' }}>Log (no sending — for testing)</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="mail_host" class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                    <input id="mail_host" type="text" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" placeholder="e.g. mail.truckroute.com.au"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
                <div>
                    <label for="mail_port" class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                    <input id="mail_port" type="number" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" placeholder="587"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
                <div>
                    <label for="mail_username" class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input id="mail_username" type="text" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}" autocomplete="off"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
                <div>
                    <label for="mail_password" class="block text-sm font-medium text-gray-700 mb-1">Password {{ $settings['mail_password_set'] ? '(saved — leave blank to keep)' : '' }}</label>
                    <input id="mail_password" type="password" name="mail_password" autocomplete="new-password"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
                <div>
                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                    <select id="mail_encryption" name="mail_encryption"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                        <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS (port 587)</option>
                        <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL (port 465)</option>
                        <option value="none" {{ old('mail_encryption', $settings['mail_encryption']) === '' ? 'selected' : '' }}>None</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="mail_from_address" class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                    <input id="mail_from_address" type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" placeholder="noreply@truckroute.com.au"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
                <div>
                    <label for="mail_from_name" class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                    <input id="mail_from_name" type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" placeholder="TruckRoute"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 mb-6">
                <h2 class="text-sm font-semibold text-gray-900 mb-3">Automated Emails</h2>
                <div class="space-y-3">
                    @foreach(\App\Support\SiteMail::toggles() as $key => $label)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $settings[$key]) ? 'checked' : '' }} class="rounded border-gray-300 text-maroon-600 shadow-sm focus:ring-maroon-500">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            @if($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">Save Email Settings</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-2xl">
        <h2 class="text-sm font-semibold text-gray-900 mb-3">Send Test Email</h2>
        <form method="POST" action="{{ route('admin.settings.email.test') }}" class="flex gap-2">
            @csrf
            <input type="email" name="test_address" value="{{ old('test_address', auth()->user()->email) }}" required placeholder="you@example.com"
                class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition whitespace-nowrap">Send Test</button>
        </form>
    </div>
</x-layouts.admin>
