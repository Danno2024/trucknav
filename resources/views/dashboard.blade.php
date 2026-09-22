<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Welcome back, {{ Auth::user()->name }}!</h3>
                            <p class="text-sm text-gray-500">Plan your next safe route or manage your saved routes.</p>
                        </div>
                        <a href="{{ route('planner') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 focus:outline-none focus:ring-2 focus:ring-maroon-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            New Route
                        </a>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                            <div class="bg-maroon-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-maroon-700">{{ $savedRoutesCount }}</div>
                                <div class="text-sm text-gray-600">Saved Routes</div>
                            </div>
                            <div class="bg-maroon-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-maroon-700">{{ number_format($totalDistance, 1) }} km</div>
                                <div class="text-sm text-gray-600">Total Distance</div>
                            </div>
                            <div class="bg-maroon-50 rounded-lg p-4">
                                <div class="text-2xl font-bold text-maroon-700">{{ $hazardsReported }}</div>
                                <div class="text-sm text-gray-600">Hazards Reported</div>
                            </div>
                        </div>

                        @if($savedRoutesCount > 0)
                            <h4 class="text-md font-semibold text-gray-900 mb-4">Your Saved Routes</h4>
                            <div class="space-y-3">
                                @foreach($savedRoutes as $route)
                                    <a href="{{ url('/planner?route=' . $route->id) }}" class="block border border-gray-200 rounded-lg p-4 hover:border-maroon-300 hover:bg-maroon-50 transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1 min-w-0">
                                                <h5 class="font-semibold text-gray-900 truncate">{{ $route->name }}</h5>
                                                <p class="text-sm text-gray-500 mt-0.5">
                                                    <span class="text-green-600 font-medium">A</span> {{ $route->origin_address }}
                                                    &rarr;
                                                    <span class="text-red-600 font-medium">B</span> {{ $route->destination_address }}
                                                </p>
                                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500">
                                                    @if($route->total_distance_km)
                                                        <span>{{ number_format($route->total_distance_km, 1) }} km</span>
                                                    @endif
                                                    @if($route->total_duration_minutes)
                                                        <span>{{ $route->total_duration_minutes }} min</span>
                                                    @endif
                                                    <span class="capitalize">{{ $route->vehicle_type }}</span>
                                                    @if($route->vehicle_height_m)
                                                        <span>H: {{ $route->vehicle_height_m }}m</span>
                                                    @endif
                                                    @if($route->vehicle_width_m)
                                                        <span>W: {{ $route->vehicle_width_m }}m</span>
                                                    @endif
                                                    @if($route->vehicle_length_m)
                                                        <span>L: {{ $route->vehicle_length_m }}m</span>
                                                    @endif
                                                    <span class="text-gray-400">{{ $route->created_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                            <button onclick="event.preventDefault(); event.stopPropagation(); deleteRoute({{ $route->id }})" class="ml-4 text-red-400 hover:text-red-600 transition" title="Delete route" aria-label="Delete route {{ $route->name }}">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No saved routes yet</h3>
                                <p class="mt-1 text-sm text-gray-500">Get started by planning your first route.</p>
                                <div class="mt-6">
                                    <a href="{{ route('planner') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-maroon-800 transition">
                                        Open Route Planner
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-md font-semibold text-gray-900">Forum Activity</h4>
                        <a href="{{ route('forums.index') }}" class="text-sm text-maroon-600 hover:text-maroon-700">View Forums</a>
                    </div>

                    @if ($forumThreads->count() > 0 || $forumPosts->count() > 0)
                        <div class="space-y-3">
                            @foreach ($forumThreads as $thread)
                                <a href="{{ route('forums.thread', $thread->slug) }}" class="block border border-gray-200 rounded-lg p-3 hover:border-maroon-300 hover:bg-maroon-50 transition">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400">Thread</span>
                                        <span class="font-medium text-gray-900 text-sm">{{ Str::limit($thread->title, 60) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $thread->category->name }} · {{ $thread->created_at->diffForHumans() }}</div>
                                </a>
                            @endforeach
                            @foreach ($forumPosts as $post)
                                <a href="{{ route('forums.thread', $post->thread->slug) }}#post-{{ $post->id }}" class="block border border-gray-200 rounded-lg p-3 hover:border-maroon-300 hover:bg-maroon-50 transition">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs text-gray-400">Reply</span>
                                        <span class="font-medium text-gray-900 text-sm">in {{ Str::limit($post->thread->title, 50) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">{{ $post->created_at->diffForHumans() }}</div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500">You haven't posted in the forums yet. <a href="{{ route('forums.index') }}" class="text-maroon-600 hover:underline">Join the conversation</a>.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        async function deleteRoute(id) {
            if (!confirm('Are you sure you want to delete this route?')) return;

            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch('{{ url("/planner") }}/' + id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                });

                if (response.ok) {
                    window.location.reload();
                }
            } catch (err) {
                console.error('Delete error:', err);
            }
        }
    </script>
</x-app-layout>
