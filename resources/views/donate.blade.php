<x-layouts.public>
    <x-slot name="title">Donate - TruckNav</x-slot>
    <x-slot name="metaDescription">Support TruckNav with a donation to help keep free heavy vehicle route planning alive for Australian drivers.</x-slot>

    <section class="py-20 bg-gray-50">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Support TruckNav</h1>
                <p class="text-lg text-gray-600">
                    TruckNav is free and always will be. If it's helped you plan safer routes,
                    consider a small donation to help keep the project running.
                </p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-8">
                <form method="POST" action="{{ url('/donate') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Donation Amount (AUD)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-lg font-semibold">$</span>
                            <input type="number" id="amount" name="amount" value="10" min="1" max="10000" step="0.01"
                                class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg text-lg focus:border-maroon-500 focus:ring-maroon-500">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-4 gap-2 mb-6">
                        <button type="button" onclick="document.getElementById('amount').value=5" class="py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-maroon-50 hover:border-maroon-300 transition">$5</button>
                        <button type="button" onclick="document.getElementById('amount').value=10" class="py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-maroon-50 hover:border-maroon-300 transition">$10</button>
                        <button type="button" onclick="document.getElementById('amount').value=25" class="py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-maroon-50 hover:border-maroon-300 transition">$25</button>
                        <button type="button" onclick="document.getElementById('amount').value=50" class="py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-maroon-50 hover:border-maroon-300 transition">$50</button>
                    </div>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-3 bg-[#0070ba] text-white font-semibold rounded-lg hover:bg-[#005ea6] transition-colors text-lg">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M7.076 21.337H2.47a.641.641 0 0 1-.633-.74L4.944.901C5.026.382 5.474 0 5.998 0h7.46c2.57 0 4.578.543 5.69 1.81 1.01 1.15 1.304 2.42 1.012 4.287-.023.143-.047.288-.077.437-.983 5.05-4.349 6.797-8.647 6.797h-2.19c-.524 0-.968.382-1.05.9l-1.12 7.106z"/>
                        </svg>
                        Donate with PayPal
                    </button>

                    <p class="mt-4 text-xs text-gray-400 text-center">
                        You'll be redirected to PayPal to complete your donation securely.
                    </p>
                </form>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">
                    Prefer to leave a review instead?
                    <a href="#!" onclick="window.history.back(); return false;" class="text-maroon-700 font-medium hover:underline">Go back</a>
                </p>
            </div>
        </div>
    </section>
</x-layouts.public>
