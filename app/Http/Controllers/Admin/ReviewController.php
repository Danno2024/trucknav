<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with('user');

        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();
        $avgRating = Review::whereNotNull('rating')->avg('rating');

        return view('admin.reviews.index', compact('reviews', 'avgRating'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')->with('success', 'Review deleted.');
    }
}
