@extends('install.layout', ['step' => 4])
@section('step', 'Admin')
@section('content')
    <h1 class="text-xl font-bold text-gray-900 mb-1">Admin Account</h1>
    <p class="text-sm text-gray-500 mb-6">Create the administrator account and choose your starting content.</p>

    @if($errors->any())
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('install.admin.store') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input id="password" type="password" name="password" required minlength="8" autocomplete="new-password"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required minlength="8" autocomplete="new-password"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-maroon-500 focus:ring-maroon-500 text-sm">
            </div>
        </div>

        <div class="mb-5">
            <span class="block text-sm font-medium text-gray-700 mb-2">Site Content</span>
            <div class="grid grid-cols-2 gap-3">
                <label class="cursor-pointer">
                    <input type="radio" name="site_content" value="demo" {{ old('site_content', 'demo') === 'demo' ? 'checked' : '' }} class="peer sr-only">
                    <div class="rounded-lg border-2 p-4 peer-checked:border-maroon-600 peer-checked:bg-maroon-50 border-gray-200 transition">
                        <div class="text-sm font-semibold text-gray-900">Demo content</div>
                        <div class="text-xs text-gray-500 mt-0.5">Sample restrictions + forum threads to explore</div>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <input type="radio" name="site_content" value="blank" {{ old('site_content') === 'blank' ? 'checked' : '' }} class="peer sr-only">
                    <div class="rounded-lg border-2 p-4 peer-checked:border-maroon-600 peer-checked:bg-maroon-50 border-gray-200 transition">
                        <div class="text-sm font-semibold text-gray-900">Blank site</div>
                        <div class="text-xs text-gray-500 mt-0.5">Empty — add your own data</div>
                    </div>
                </label>
            </div>
            <p class="text-xs text-gray-400 mt-1.5">Forum categories are always created so the forum works out of the box.</p>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('install.settings') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back</a>
            <button type="submit" class="px-5 py-2.5 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">
                Finish Installation &rarr;
            </button>
        </div>
    </form>
@endsection
