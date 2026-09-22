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

    public function destroy(SavedRoute $route)
    {
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'Route deleted.');
    }
}
