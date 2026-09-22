<?php

namespace App\Http\Controllers;

use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $categories = ForumCategory::where('is_active', true)
            ->withCount('threads')
            ->orderBy('sort_order')
            ->get();

        return view('forums.index', compact('categories'));
    }

    public function create()
    {
        $categories = ForumCategory::where('is_active', true)->orderBy('sort_order')->get();

        return view('forums.create', compact('categories'));
    }

    public function category(ForumCategory $category)
    {
        $threads = ForumThread::where('category_id', $category->id)
            ->with('user')
            ->orderByDesc('is_pinned')
            ->orderByDesc('last_post_at')
            ->paginate(20);

        return view('forums.category', compact('category', 'threads'));
    }

    public function thread(ForumThread $thread)
    {
        $thread->incrementViews();

        $posts = ForumThread::where('id', $thread->id)
            ->with(['posts.user', 'user', 'category'])
            ->first()
            ->posts()
            ->with('user')
            ->orderBy('created_at')
            ->get();

        $category = $thread->category;

        return view('forums.thread', compact('thread', 'posts', 'category'));
    }

    public function storeThread(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:forum_categories,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $thread = ForumThread::create([
            'category_id' => $validated['category_id'],
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
        ]);

        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('forums.thread', $thread->slug)
            ->with('success', 'Thread created successfully.');
    }

    public function storeReply(Request $request, ForumThread $thread)
    {
        if ($thread->is_locked) {
            return back()->withErrors(['content' => 'This thread is locked.']);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        ForumPost::create([
            'thread_id' => $thread->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()->route('forums.thread', $thread->slug)
            ->with('success', 'Reply posted.');
    }

    public function updatePost(Request $request, ForumPost $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $post->update([
            'content' => $validated['content'],
            'is_edited' => true,
        ]);

        return redirect()->route('forums.thread', $post->thread->slug)
            ->with('success', 'Post updated.');
    }

    public function destroyPost(ForumPost $post)
    {
        if ($post->user_id !== auth()->id()) {
            abort(403);
        }

        $slug = $post->thread->slug;
        $post->delete();

        return redirect()->route('forums.thread', $slug)
            ->with('success', 'Post deleted.');
    }
}
