<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SavedRoute;
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

        $route->update($validated);

        return redirect()->route('admin.routes.index')->with('success', 'Route updated.');
    }

    public function destroy(SavedRoute $route)
    {
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Route deleted.');
    }
}
