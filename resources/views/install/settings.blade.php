@extends('install.layout', ['step' => 3])
@section('step', 'Settings')
@section('content')
    <h1 class="text-xl font-bold text-gray-900 mb-1">Application Settings</h1>
    <p class="text-sm text-gray-500 mb-6">Database migrated successfully. Now configure your site details.</p>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.settings.store') }}">
        @csrf

        <div class="mb-4">
            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-1">Site Name</label>
            <input id="app_name" type="text" name="app_name" value="{{ old('app_name', 'TruckRoute') }}" required maxlength="100"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
        </div>

        <div class="mb-5">
            <label for="app_url" class="block text-sm font-medium text-gray-700 mb-1">Site URL</label>
            <input id="app_url" type="url" name="app_url" value="{{ old('app_url', request()->getSchemeAndHttpHost()) }}" required
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            <p class="text-xs text-gray-400 mt-1">The public address of this site, including https:// if applicable.</p>
        </div>

        <p class="text-xs text-gray-400 mb-5">Production mode is enabled automatically (debug off).</p>

        <div class="flex items-center justify-between">
            <a href="{{ route('install.database') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back</a>
            <button type="submit" class="px-5 py-2.5 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">
                Continue &rarr;
            </button>
        </div>
    </form>
@endsection
