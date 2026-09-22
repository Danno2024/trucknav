<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <a href="{{ route('forums.index') }}" class="text-maroon-700 hover:text-maroon-800">Forums</a>
            <span class="text-gray-400 mx-1">/</span>
            <a href="{{ route('forums.category', $category->slug) }}" class="text-maroon-700 hover:text-maroon-800">{{ $category->name }}</a>
            <span class="text-gray-400 mx-1">/</span>
            <span class="text-gray-700">{{ Str::limit($thread->title, 50) }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-300 text-green-800 rounded-lg px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center gap-2 mb-2">
                        @if ($thread->is_pinned)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">Pinned</span>
                        @endif
                        @if ($thread->is_locked)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">Locked</span>
                        @endif
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $thread->title }}</h1>
                    <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                        <span>Started by <strong class="text-gray-700">{{ $thread->user->name }}</strong></span>
                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                        <span>{{ $thread->views_count }} {{ Str::plural('view', $thread->views_count) }}</span>
                        <span>{{ $thread->replies_count }} {{ Str::plural('reply', $thread->replies_count) }}</span>
                    </div>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach ($posts as $post)
                        <div class="p-6" id="post-{{ $post->id }}">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-maroon-100 flex items-center justify-center shrink-0">
                                    <span class="text-sm font-semibold text-maroon-700">{{ substr($post->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="font-medium text-gray-900">{{ $post->user->name }}</span>
                                        @if ($post->user->isAdmin())
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-maroon-100 text-maroon-800">Admin</span>
                                        @endif
                                        <span class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</span>
                                        @if ($post->is_edited)
                                            <span class="text-xs text-gray-400">(edited)</span>
                                        @endif
                                    </div>
                                    <div class="prose prose-sm max-w-none text-gray-700">{!! $post->content !!}</div>

                                    @if ($post->user_id === auth()->id())
                                        <div class="flex items-center gap-3 mt-3">
                                            <button onclick="document.getElementById('edit-{{ $post->id }}').classList.toggle('hidden')" class="text-xs text-gray-500 hover:text-maroon-700">Edit</button>
                                            <form action="{{ route('forums.post.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs text-gray-500 hover:text-red-600">Delete</button>
                                            </form>
                                        </div>

                                        <div id="edit-{{ $post->id }}" class="hidden mt-4">
                                            <form action="{{ route('forums.post.update', $post->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <x-editor name="content" :value="$post->content" :id="'edit-editor-' . $post->id" />
                                                <div class="flex items-center gap-2 mt-3">
                                                    <button type="submit" class="px-3 py-1.5 bg-maroon-700 text-white text-sm rounded-lg hover:bg-maroon-800">Save</button>
                                                    <button type="button" onclick="document.getElementById('edit-{{ $post->id }}').classList.add('hidden')" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if ($thread->is_locked)
                <div class="bg-gray-100 border border-gray-300 rounded-lg p-4 text-center text-gray-600">
                    This thread is locked. No new replies can be posted.
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Post a Reply</h3>
                    @auth
                        <form action="{{ route('forums.reply.store', $thread->slug) }}" method="POST">
                            @csrf
                            <x-editor name="content" placeholder="Write your reply..." />
                            @error('content')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <div class="mt-4">
                                <button type="submit" class="px-4 py-2 bg-maroon-700 text-white rounded-lg hover:bg-maroon-800 transition-colors text-sm font-medium">Post Reply</button>
                            </div>
                        </form>
                    @else
                        <p class="text-gray-500"><a href="{{ route('login') }}" class="text-maroon-700 hover:underline">Log in</a> to post a reply.</p>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
