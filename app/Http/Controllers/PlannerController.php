<?php

namespace App\Http\Controllers;

use App\Models\RoadRestriction;
use App\Models\SavedRoute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlannerController extends Controller
{
    public function index()
    {
        $restrictions = RoadRestriction::where('status', 'active')
            ->select('id', 'restriction_type', 'address', 'latitude', 'longitude', 'severity', 'verified', 'description')
            ->get();

        return view('planner', compact('restrictions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'origin_address' => 'required|string|max:255',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'destination_address' => 'required|string|max:255',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'waypoints' => 'nullable|array',
            'vehicle_type' => 'required|string|in:truck,bus,coach',
            'vehicle_weight_kg' => 'nullable|integer|min:0',
            'vehicle_height_cm' => 'nullable|integer|min:0',
            'vehicle_width_cm' => 'nullable|integer|min:0',
            'vehicle_length_cm' => 'nullable|integer|min:0',
            'total_distance_km' => 'nullable|numeric|min:0',
            'total_duration_minutes' => 'nullable|integer|min:0',
            'route_geometry' => 'nullable|array',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['user_id'] = Auth::id();

        $route = SavedRoute::create($validated);

        return response()->json([
            'success' => true,
            'route' => $route,
            'message' => 'Route saved successfully!',
        ]);
    }

    public function show(SavedRoute $route)
    {
        if ($route->user_id !== Auth::id()) {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'route' => $route,
        ]);
    }

    public function destroy(SavedRoute $route)
    {
        if ($route->user_id !== Auth::id()) {
            abort(403);
        }

        $route->delete();

        return response()->json([
            'success' => true,
            'message' => 'Route deleted successfully!',
        ]);
    }

    public function reportRestriction(Request $request)
    {
        $validated = $request->validate([
            'restriction_type' => 'required|string|in:low_bridge,weight_limit,height_limit,width_limit,road_ban,rough_road,other',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable|string|max:500',
            'severity' => 'required|string|in:low,medium,high,critical',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'active';

        $restriction = RoadRestriction::create($validated);

        return response()->json([
            'success' => true,
            'restriction' => $restriction,
            'message' => 'Restriction reported successfully! Thank you for helping other drivers.',
        ]);
    }
}
