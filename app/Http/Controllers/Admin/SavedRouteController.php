<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavedRoute;
use App\Models\User;
use Illuminate\Http\Request;

class SavedRouteController extends Controller
{
    public function index(Request $request)
    {
        $query = SavedRoute::with('user');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('origin_address', 'like', "%{$search}%")
                  ->orWhere('destination_address', 'like', "%{$search}%");
            });
        }

        $routes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.routes.index', compact('routes'));
    }

    public function create()
    {
        $users = User::where('is_active', true)->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.routes.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'origin_address' => 'required|string|max:255',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'destination_address' => 'required|string|max:255',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'vehicle_type' => 'required|string|max:50',
            'vehicle_weight_kg' => 'nullable|integer|min:0',
            'vehicle_height_m' => 'nullable|numeric|min:0|max:10',
            'vehicle_width_m' => 'nullable|numeric|min:0|max:10',
            'vehicle_length_m' => 'nullable|numeric|min:0|max:30',
            'total_distance_km' => 'nullable|numeric|min:0',
            'total_duration_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        SavedRoute::create($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Route created.');
    }

    public function edit(SavedRoute $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    public function update(Request $request, SavedRoute $route)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'origin_address' => 'required|string|max:255',
            'origin_lat' => 'required|numeric',
            'origin_lng' => 'required|numeric',
            'destination_address' => 'required|string|max:255',
            'destination_lat' => 'required|numeric',
            'destination_lng' => 'required|numeric',
            'vehicle_type' => 'required|string|max:50',
            'vehicle_weight_kg' => 'nullable|integer|min:0',
            'vehicle_height_m' => 'nullable|numeric|min:0|max:10',
            'vehicle_width_m' => 'nullable|numeric|min:0|max:10',
            'vehicle_length_m' => 'nullable|numeric|min:0|max:30',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validated['name'] !== $route->name) {
            $newRoute = $route->replicate();
            $newRoute->name = $validated['name'];
            $newRoute->created_at = now();
            $newRoute->updated_at = now();
            $newRoute->push();

            return redirect()->route('admin.routes.index')->with('success', 'New route created from existing route.');
        }

        $route->update($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Route updated.');
    }

    public function destroy(SavedRoute $route)
    {
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Route deleted.');
    }
}
