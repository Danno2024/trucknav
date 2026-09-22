<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\RoadRestriction;
use App\Models\Review;
use App\Models\SavedRoute;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'total_routes' => SavedRoute::count(),
            'total_restrictions' => RoadRestriction::count(),
            'unverified_restrictions' => RoadRestriction::where('verified', false)->count(),
            'total_donations' => Donation::where('status', 'completed')->sum('amount'),
            'total_reviews' => Review::count(),
            'avg_rating' => Review::whereNotNull('rating')->avg('rating'),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentRestrictions = RoadRestriction::latest()->take(5)->get();
        $recentRoutes = SavedRoute::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentRestrictions', 'recentRoutes'));
    }
}
