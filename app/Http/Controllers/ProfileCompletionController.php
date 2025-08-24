<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\District;
use App\Models\Region;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileCompletionController extends Controller
{
    public function regions()
    {
        return response()->json(Region::orderBy('name')->get(['id','name']));
    }

    public function districts(Request $request)
    {
        $request->validate(['region_id' => 'required|exists:regions,id']);
        $items = District::where('region_id', $request->integer('region_id'))
            ->orderBy('name')->get(['id','name']);
        return response()->json($items);
    }

    public function categories()
    {
        return response()->json(Category::where('is_active', true)->orderBy('name')->get(['id','name']));
    }

    public function stations(Request $request)
    {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
            'category_id' => 'nullable|exists:categories,id',
        ]);
        $q = Station::where('district_id', $request->integer('district_id'));
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->integer('category_id'));
        }
        return response()->json($q->orderBy('name')->get(['id','name']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'required|exists:districts,id',
            'category_id' => 'required|exists:categories,id',
            'station_name' => 'required|string|min:3',
        ]);

        $user = Auth::user();

        // Create or reuse station within the chosen district/category
        $station = Station::firstOrCreate([
            'district_id' => (int) $validated['district_id'],
            'category_id' => (int) $validated['category_id'],
            'name' => trim($validated['station_name']),
        ]);

        $user->region_id = (int) $validated['region_id'];
        $user->district_id = (int) $validated['district_id'];
        $user->category_id = (int) $validated['category_id'];
        $user->station_id = $station->id;
        $user->save();

        return redirect()->route('dashboard')->with('status', 'Profile updated successfully');
    }
}
