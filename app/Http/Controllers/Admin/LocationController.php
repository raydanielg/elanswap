<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Models\District;

class LocationController extends Controller
{
    public function index()
    {
        $regions = Region::withCount('districts')->orderBy('name')->get();
        $districts = District::with('region')->orderBy('name')->limit(20)->get();
        $counts = [
            'regions' => Region::count(),
            'districts' => District::count(),
        ];
        return view('admin.locations.index', compact('regions','districts','counts'));
    }
}
