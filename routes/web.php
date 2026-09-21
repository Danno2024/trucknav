<?php

use App\Http\Controllers\PlannerController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
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

require __DIR__.'/auth.php';
