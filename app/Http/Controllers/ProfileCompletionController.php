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
        // Only return two sectors: Elimu and Afya
        return response()->json(
            Category::where('is_active', true)
                ->whereIn('name', ['Elimu','Afya'])
                ->orderBy('name')
                ->get(['id','name'])
        );
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
            // sector-specific
            'qualification_level' => 'nullable|in:degree,diploma',
            'edu_subject_one' => 'nullable|string|min:2',
            'edu_subject_two' => 'nullable|string|min:2',
            'health_department' => 'nullable|string|min:2',
        ]);

        $user = Auth::user();

        // Determine sector name for conditional requirements
        $category = Category::findOrFail((int) $validated['category_id']);
        $sector = strtolower($category->name);

        // Enforce sector-specific required fields
        if ($sector === 'elimu') {
            $request->validate([
                'qualification_level' => 'required|in:degree,diploma',
                'edu_subject_one' => 'required|string|min:2',
                'edu_subject_two' => 'required|string|min:2',
            ]);
        } elseif ($sector === 'afya') {
            $request->validate([
                'qualification_level' => 'required|in:degree,diploma',
                'health_department' => 'required|string|min:2',
            ]);
        }

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
        // save sector-specific
        $user->qualification_level = $request->string('qualification_level');
        $user->edu_subject_one = $request->string('edu_subject_one');
        $user->edu_subject_two = $request->string('edu_subject_two');
        $user->health_department = $request->string('health_department');
        $user->save();

        return redirect()->route('dashboard')->with('status', 'Profile updated successfully');
    }
}
