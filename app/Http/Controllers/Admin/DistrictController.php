<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $regionId = $request->integer('region_id');
        $query = District::with('region')->orderBy('name');
        if ($regionId) {
            $query->where('region_id', $regionId);
        }
        $districts = $query->paginate(20)->withQueryString();
        $regions = Region::orderBy('name')->get();
        return view('admin.locations.districts', compact('districts','regions','regionId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:100',
        ]);
        // Ensure unique per region
        $exists = District::where('region_id', $data['region_id'])->where('name', $data['name'])->exists();
        if ($exists) {
            return back()->with('error', 'District already exists in this region');
        }
        District::create($data);
        return back()->with('status', 'District added');
    }

    public function update(Request $request, District $district)
    {
        $data = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:100',
        ]);
        $exists = District::where('region_id', $data['region_id'])
            ->where('name', $data['name'])
            ->where('id', '!=', $district->id)
            ->exists();
        if ($exists) {
            return back()->with('error', 'District already exists in this region');
        }
        $district->update($data);
        return back()->with('status', 'District updated');
    }

    public function destroy(District $district)
    {
        if ($district->stations()->exists()) {
            return back()->with('error', 'Cannot delete district with stations');
        }
        $district->delete();
        return back()->with('status', 'District deleted');
    }
}
