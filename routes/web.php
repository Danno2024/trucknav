<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DonationController;
use App\Http\Controllers\Admin\RestrictionController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SavedRouteController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DonationController as PublicDonationController;
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

    return view('dashboard', compact('savedRoutes', 'savedRoutesCount', 'totalDistance', 'hazardsReported'));
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
    Route::get('/restrictions/{restriction}', [RestrictionController::class, 'show'])->name('restrictions.show');
    Route::put('/restrictions/{restriction}', [RestrictionController::class, 'update'])->name('restrictions.update');
    Route::post('/restrictions/{restriction}/verify', [RestrictionController::class, 'verify'])->name('restrictions.verify');
    Route::delete('/restrictions/{restriction}', [RestrictionController::class, 'destroy'])->name('restrictions.destroy');

    Route::get('/routes', [SavedRouteController::class, 'index'])->name('routes.index');
    Route::delete('/routes/{route}', [SavedRouteController::class, 'destroy'])->name('routes.destroy');

    Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/settings/system', [SettingsController::class, 'system'])->name('settings.system');
    Route::put('/settings/system', [SettingsController::class, 'updateSystem'])->name('settings.system.update');
    Route::get('/settings/paypal', [SettingsController::class, 'paypal'])->name('settings.paypal');
    Route::put('/settings/paypal', [SettingsController::class, 'updatePaypal'])->name('settings.paypal.update');
});

require __DIR__.'/auth.php';
