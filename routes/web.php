<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminForumController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\RestrictionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SavedRouteController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DonationController as PublicDonationController;
use App\Http\Controllers\ForumController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController as PublicReviewController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    $savedRoutes = $user->savedRoutes()->latest()->get();
    $savedRoutesCount = $savedRoutes->count();
    $totalDistance = (float) $savedRoutes->sum('total_distance_km');
    $hazardsReported = $user->roadRestrictions()->count();
    $forumThreads = $user->forumThreads()->latest()->limit(5)->get();
    $forumPosts = $user->forumPosts()->latest()->limit(5)->get();

    return view('dashboard', compact('savedRoutes', 'savedRoutesCount', 'totalDistance', 'hazardsReported', 'forumThreads', 'forumPosts'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/planner', [PlannerController::class, 'index'])->name('planner');
    Route::post('/planner/save', [PlannerController::class, 'store'])->name('planner.save');
    Route::get('/planner/{route}', [PlannerController::class, 'show'])->name('planner.show');
    Route::delete('/planner/{route}', [PlannerController::class, 'destroy'])->name('planner.destroy');
    Route::post('/planner/report-restriction', [PlannerController::class, 'reportRestriction'])->name('planner.report-restriction');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/donate', [PublicDonationController::class, 'index'])->name('donate');
Route::post('/donate', [PublicDonationController::class, 'store'])->name('donate.store');
Route::get('/donate/return', [PublicDonationController::class, 'paypalReturn'])->name('donate.return');
Route::get('/donate/cancel', [PublicDonationController::class, 'paypalCancel'])->name('donate.cancel');

Route::middleware('auth')->group(function () {
    Route::post('/reviews', [PublicReviewController::class, 'store'])->name('reviews.store');
});

Route::prefix('forums')->name('forums.')->group(function () {
    Route::get('/', [ForumController::class, 'index'])->name('index');
    Route::get('/create', [ForumController::class, 'create'])->name('create');
    Route::get('/{category}', [ForumController::class, 'category'])->name('category');
    Route::get('/thread/{thread}', [ForumController::class, 'thread'])->name('thread');
});

Route::middleware('auth')->prefix('forums')->name('forums.')->group(function () {
    Route::post('/thread', [ForumController::class, 'storeThread'])->name('thread.store');
    Route::post('/thread/{thread}/reply', [ForumController::class, 'storeReply'])->name('reply.store');
    Route::put('/post/{post}', [ForumController::class, 'updatePost'])->name('post.update');
    Route::delete('/post/{post}', [ForumController::class, 'destroyPost'])->name('post.destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/restrictions', [RestrictionController::class, 'index'])->name('restrictions.index');
    Route::get('/restrictions/create', [RestrictionController::class, 'create'])->name('restrictions.create');
    Route::post('/restrictions', [RestrictionController::class, 'store'])->name('restrictions.store');
    Route::get('/restrictions/{restriction}', [RestrictionController::class, 'show'])->name('restrictions.show');
    Route::put('/restrictions/{restriction}', [RestrictionController::class, 'update'])->name('restrictions.update');
    Route::post('/restrictions/{restriction}/verify', [RestrictionController::class, 'verify'])->name('restrictions.verify');
    Route::delete('/restrictions/{restriction}', [RestrictionController::class, 'destroy'])->name('restrictions.destroy');

    Route::get('/routes', [SavedRouteController::class, 'index'])->name('routes.index');
    Route::get('/routes/create', [SavedRouteController::class, 'create'])->name('routes.create');
    Route::post('/routes', [SavedRouteController::class, 'store'])->name('routes.store');
    Route::get('/routes/{route}/edit', [SavedRouteController::class, 'edit'])->name('routes.edit');
    Route::put('/routes/{route}', [SavedRouteController::class, 'update'])->name('routes.update');
    Route::delete('/routes/{route}', [SavedRouteController::class, 'destroy'])->name('routes.destroy');

    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/forums', [AdminForumController::class, 'dashboard'])->name('forums.dashboard');

    Route::prefix('forums/categories')->name('forums.categories.')->group(function () {
        Route::get('/', [AdminForumController::class, 'categoriesIndex'])->name('index');
        Route::get('/create', [AdminForumController::class, 'categoriesCreate'])->name('create');
        Route::post('/', [AdminForumController::class, 'categoriesStore'])->name('store');
        Route::get('/{category}/edit', [AdminForumController::class, 'categoriesEdit'])->name('edit');
        Route::put('/{category}', [AdminForumController::class, 'categoriesUpdate'])->name('update');
        Route::delete('/{category}', [AdminForumController::class, 'categoriesDestroy'])->name('destroy');
    });

    Route::prefix('forums/threads')->name('forums.threads.')->group(function () {
        Route::get('/', [AdminForumController::class, 'threadsIndex'])->name('index');
        Route::delete('/{thread}', [AdminForumController::class, 'threadsDestroy'])->name('destroy');
        Route::post('/{thread}/pin', [AdminForumController::class, 'threadsPin'])->name('pin');
        Route::post('/{thread}/lock', [AdminForumController::class, 'threadsLock'])->name('lock');
    });

    Route::prefix('forums/posts')->name('forums.posts.')->group(function () {
        Route::get('/', [AdminForumController::class, 'postsIndex'])->name('index');
        Route::delete('/{post}', [AdminForumController::class, 'postsDestroy'])->name('destroy');
    });

    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::put('/settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
    Route::get('/settings/paypal', [SettingsController::class, 'paypal'])->name('settings.paypal');
    Route::put('/settings/paypal', [SettingsController::class, 'updatePaypal'])->name('settings.paypal.update');
});

require __DIR__.'/auth.php';
