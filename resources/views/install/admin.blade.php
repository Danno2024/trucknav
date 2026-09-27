@extends('install.layout', ['step' => 4])
@section('step', 'Admin')
@section('content')
    <h1 class="text-xl font-bold text-gray-900 mb-1">Admin Account</h1>
    <p class="text-sm text-gray-500 mb-6">Create the administrator account. Default forum categories and sample restrictions are seeded automatically.</p>

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

        <div class="flex items-center justify-between">
            <a href="{{ route('install.settings') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Back</a>
            <button type="submit" class="px-5 py-2.5 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition">
                Finish Installation &rarr;
            </button>
        </div>
    </form>
@endsection
