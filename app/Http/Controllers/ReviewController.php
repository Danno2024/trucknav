<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
                'platform' => 'nullable|string|in:app,google,producthunt,other',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        }

        $validated['user_id'] = Auth::id();
        $validated['platform'] = $validated['platform'] ?? 'app';

        $review = Review::create($validated);

        return response()->json([
            'success' => true,
            'review' => $review,
            'message' => 'Thank you for your feedback!',
        ]);
    }
}
