<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index()
    {
        $regions = Region::orderBy('name')->paginate(20);
        return view('admin.locations.regions', compact('regions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:regions,name',
        ]);
        Region::create($data);
        return back()->with('status', 'Region added');
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'name' => 'required|string|max:100|unique:regions,name,' . $region->id,
        ]);
        $region->update($data);
        return back()->with('status', 'Region updated');
    }

    public function destroy(Region $region)
    {
        // Optionally prevent delete if has districts
        if ($region->districts()->exists()) {
            return back()->with('error', 'Cannot delete region with districts');
        }
        $region->delete();
        return back()->with('status', 'Region deleted');
    }
}
