<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoadRestriction;
use Illuminate\Http\Request;

class RestrictionController extends Controller
{
    public function index(Request $request)
    {
        $query = RoadRestriction::with('user');

        if ($search = $request->input('search')) {
            $query->where('address', 'like', "%{$search}%");
        }

        if ($type = $request->input('type')) {
            $query->where('restriction_type', $type);
        }

        if ($status = $request->input('status')) {
            if ($status === 'verified') {
                $query->where('verified', true);
            } elseif ($status === 'unverified') {
                $query->where('verified', false);
            }
        }

        $restrictions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.restrictions.index', compact('restrictions'));
    }

    public function create()
    {
        return view('admin.restrictions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'restriction_type' => 'required|in:low_bridge,height_limit,weight_limit,width_limit,road_ban,rough_road,other',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:active,inactive',
        ]);

        RoadRestriction::create(array_merge($validated, [
            'user_id' => $request->user()->id,
            'verified' => true,
            'verification_count' => 1,
            'reporter_count' => 1,
        ]));

        return redirect()->route('admin.restrictions.index')->with('success', 'Restriction created.');
    }

    public function show(RoadRestriction $restriction)
    {
        $restriction->load('user');
        return view('admin.restrictions.show', compact('restriction'));
    }

    public function verify(RoadRestriction $restriction)
    {
        $restriction->update([
            'verified' => true,
            'verification_count' => $restriction->verification_count + 1,
        ]);

        return back()->with('success', 'Restriction verified.');
    }

    public function update(Request $request, RoadRestriction $restriction)
    {
        $validated = $request->validate([
            'restriction_type' => 'required|in:low_bridge,height_limit,weight_limit,width_limit,road_ban,rough_road,other',
            'severity' => 'required|in:low,medium,high,critical',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ]);

        $restriction->update($validated);
        return back()->with('success', 'Restriction updated.');
    }

    public function destroy(RoadRestriction $restriction)
    {
        $restriction->delete();
        return redirect()->route('admin.restrictions.index')->with('success', 'Restriction deleted.');
    }
}
