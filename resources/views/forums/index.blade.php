<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Community Forums') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between mb-6">
                <p class="text-gray-600">Browse categories and join the conversation.</p>
                <a href="{{ route('forums.create') }}" class="inline-flex items-center px-4 py-2 bg-maroon-700 text-white rounded-lg hover:bg-maroon-800 transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    New Thread
                </a>
            </div>

            <div class="space-y-6">
                @forelse ($categories as $category)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        {{-- Category Header --}}
                        <div class="bg-maroon-50 border-b border-maroon-100 px-5 py-3">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <svg class="w-5 h-5 text-maroon-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6Zm0 9.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25a2.25 2.25 0 0 1-2.25-2.25v-2.25Z"/></svg>
                                    <a href="{{ route('forums.category', $category->slug) }}" class="text-lg font-bold text-maroon-800 hover:text-maroon-900">{{ $category->name }}</a>
                                </div>
                                <div class="hidden sm:flex items-center gap-6 text-xs text-gray-500">
                                    <div class="text-center">
                                        <div class="font-semibold text-gray-700 text-sm">{{ $category->threads_count }}</div>
                                        threads
                                    </div>
                                    <div class="text-center">
                                        <div class="font-semibold text-gray-700 text-sm">{{ $category->total_posts_count }}</div>
                                        posts
                                    </div>
                                </div>
                            </div>
                            @if ($category->description)
                                <p class="text-sm text-gray-500 mt-1 ml-8">{{ $category->description }}</p>
                            @endif
                        </div>

                        {{-- Thread List --}}
                        <div class="divide-y divide-gray-100">
                            @forelse ($category->threads as $thread)
                                <div class="flex items-center gap-4 px-5 py-3 hover:bg-gray-50 transition-colors">
                                    {{-- Thread Status Icon --}}
                                    <div class="shrink-0 hidden sm:block">
                                        @if ($thread->is_pinned)
                                            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 24 24"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z"/></svg>
                                            </div>
                                        @elseif ($thread->posts_count > 0)
                                            <div class="w-9 h-9 rounded-full bg-maroon-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-maroon-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                                            </div>
                                        @else
                                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/></svg>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Thread Info --}}
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            @if ($thread->is_pinned)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Pinned</span>
                                            @endif
                                            @if ($thread->is_locked)
                                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Locked</span>
                                            @endif
                                            <a href="{{ route('forums.thread', $thread->slug) }}" class="font-medium text-gray-900 hover:text-maroon-700 truncate">{{ $thread->title }}</a>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-0.5">
                                            by <span class="text-gray-700">{{ $thread->user->name }}</span>
                                        </div>
                                    </div>

                                    {{-- Stats (hidden on mobile) --}}
                                    <div class="hidden sm:flex items-center gap-6 text-center shrink-0">
                                        <div class="w-16">
                                            <div class="font-semibold text-gray-900 text-sm">{{ $thread->posts_count }}</div>
                                            <div class="text-xs text-gray-500">replies</div>
                                        </div>
                                        <div class="w-16">
                                            <div class="font-semibold text-gray-900 text-sm">{{ $thread->views_count }}</div>
                                            <div class="text-xs text-gray-500">views</div>
                                        </div>
                                        <div class="w-32 text-right">
                                            @if ($thread->last_post_at)
                                                <div class="text-xs text-gray-500">{{ $thread->last_post_at->diffForHumans() }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="px-5 py-8 text-center text-sm text-gray-400">
                                    No threads yet. <a href="{{ route('forums.create') }}?category={{ $category->id }}" class="text-maroon-600 hover:underline">Start the first one!</a>
                                </div>
                            @endforelse

                            @if ($category->threads_count > 5)
                                <div class="px-5 py-3 text-center">
                                    <a href="{{ route('forums.category', $category->slug) }}" class="text-sm text-maroon-600 hover:text-maroon-700 font-medium">
                                        View all {{ $category->threads_count }} threads &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                        <p class="text-gray-500">No forum categories yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
