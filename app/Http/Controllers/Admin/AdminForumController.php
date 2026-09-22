<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumCategory;
use App\Models\ForumPost;
use App\Models\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminForumController extends Controller
{
    public function dashboard()
    {
        $totalThreads = ForumThread::count();
        $totalPosts = ForumPost::count();
        $totalCategories = ForumCategory::count();
        $recentThreads = ForumThread::with('user', 'category')->latest()->limit(10)->get();

        return view('admin.forums.dashboard', compact('totalThreads', 'totalPosts', 'totalCategories', 'recentThreads'));
    }

    // ─── Categories ───────────────────────────────────────────

    public function categoriesIndex()
    {
        $categories = ForumCategory::withCount('threads')->orderBy('sort_order')->get();

        return view('admin.forums.categories.index', compact('categories'));
    }

    public function categoriesCreate()
    {
        return view('admin.forums.categories.create');
    }

    public function categoriesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:forum_categories,name',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        ForumCategory::create($validated);

        return redirect()->route('admin.forums.categories.index')
            ->with('success', 'Category created.');
    }

    public function categoriesEdit(ForumCategory $category)
    {
        return view('admin.forums.categories.edit', compact('category'));
    }

    public function categoriesUpdate(Request $request, ForumCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:forum_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.forums.categories.index')
            ->with('success', 'Category updated.');
    }

    public function categoriesDestroy(ForumCategory $category)
    {
        $category->delete();

        return redirect()->route('admin.forums.categories.index')
            ->with('success', 'Category deleted.');
    }

    // ─── Threads ──────────────────────────────────────────────

    public function threadsIndex(Request $request)
    {
        $query = ForumThread::with(['user', 'category']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $threads = $query->latest('last_post_at')->paginate(20);
        $categories = ForumCategory::orderBy('sort_order')->get();

        return view('admin.forums.threads.index', compact('threads', 'categories'));
    }

    public function threadsDestroy(ForumThread $thread)
    {
        $thread->delete();

        return redirect()->route('admin.forums.threads.index')
            ->with('success', 'Thread deleted.');
    }

    public function threadsPin(ForumThread $thread)
    {
        $thread->update(['is_pinned' => !$thread->is_pinned]);

        return back()->with('success', $thread->is_pinned ? 'Thread pinned.' : 'Thread unpinned.');
    }

    public function threadsLock(ForumThread $thread)
    {
        $thread->update(['is_locked' => !$thread->is_locked]);

        return back()->with('success', $thread->is_locked ? 'Thread locked.' : 'Thread unlocked.');
    }

    // ─── Posts ────────────────────────────────────────────────

    public function postsIndex(Request $request)
    {
        $query = ForumPost::with(['user', 'thread']);

        if ($search = $request->input('search')) {
            $query->where('content', 'like', "%{$search}%");
        }

        $posts = $query->latest()->paginate(20);

        return view('admin.forums.posts.index', compact('posts'));
    }

    public function postsDestroy(ForumPost $post)
    {
        $slug = $post->thread->slug;
        $post->delete();

        return redirect()->route('admin.forums.threads.index')
            ->with('success', 'Post deleted.');
    }
}
