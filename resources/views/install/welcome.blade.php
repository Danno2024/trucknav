@extends('install.layout', ['step' => 1])
@section('step', 'Requirements')
@section('content')
    <h1 class="text-xl font-bold text-gray-900 mb-1">Welcome to TruckRoute</h1>
    <p class="text-sm text-gray-500 mb-6">This wizard will set up your application. First, let's check the server meets all requirements.</p>

    <div class="space-y-2 mb-6">
        @foreach($checks as $check)
            <div class="flex items-start gap-3 px-3 py-2 rounded-lg {{ $check['pass'] ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-200' }}">
                @if($check['pass'])
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                @else
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
                @endif
                <div class="min-w-0">
                    <div class="text-sm font-medium {{ $check['pass'] ? 'text-gray-900' : 'text-red-800' }}">{{ $check['label'] }}</div>
                    <div class="text-xs {{ $check['pass'] ? 'text-gray-400' : 'text-red-600' }} truncate">{{ $check['hint'] }}</div>
                </div>
            </div>
        @endforeach
    </div>

    @if($blocked)
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            Some requirements failed. Fix them, then <a href="{{ route('install.welcome') }}" class="underline font-medium">re-check</a>.
        </div>
    @endif

    <div class="flex justify-end">
        <a href="{{ route('install.database') }}" class="px-5 py-2.5 rounded-lg text-sm font-semibold transition {{ $blocked ? 'bg-gray-200 text-gray-400 pointer-events-none' : 'bg-maroon-700 text-white hover:bg-maroon-800' }}">
            Continue &rarr;
        </a>
    </div>
@endsection
