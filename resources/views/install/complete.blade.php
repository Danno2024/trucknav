@extends('install.layout', ['step' => 5])
@section('step', 'Done')
@section('content')
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
        </div>
        <h1 class="text-xl font-bold text-gray-900 mb-1">Installation Complete</h1>
        <p class="text-sm text-gray-500">TruckRoute is ready to use.</p>
    </div>

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6 text-sm">
        <div class="flex justify-between py-1">
            <span class="text-gray-500">Admin email</span>
            <span class="font-medium text-gray-900">{{ $admin?->email }}</span>
        </div>
        <div class="flex justify-between py-1">
            <span class="text-gray-500">Admin panel</span>
            <a href="{{ url('/admin/login') }}" class="font-medium text-maroon-700 hover:underline">{{ url('/admin/login') }}</a>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <a href="{{ url('/') }}" class="px-4 py-2.5 bg-maroon-700 text-white rounded-lg text-sm font-semibold hover:bg-maroon-800 transition text-center">
            Visit Site
        </a>
        <a href="{{ url('/admin/login') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition text-center">
            Admin Login
        </a>
    </div>
@endsection
