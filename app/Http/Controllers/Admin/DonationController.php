<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::with('user');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $donations = $query->latest()->paginate(15)->withQueryString();

        $totalCompleted = Donation::where('status', 'completed')->sum('amount');
        $totalPending = Donation::where('status', 'pending')->sum('amount');
        $count = Donation::count();

        return view('admin.donations.index', compact('donations', 'totalCompleted', 'totalPending', 'count'));
    }
}
