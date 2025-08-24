<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Region;
use Illuminate\Http\Request;

class DestinationController extends Controller
{
    public function index()
    {
        // List all regions with count of applications targeting the region (to_region)
        $regions = Region::query()
            ->withCount('applicationsTo')
            ->orderBy('name')
            ->get();

        return view('destinations.index', compact('regions'));
    }

    public function show(Region $region, Request $request)
    {
        // List applications where destination is this region
        $apps = Application::query()
            ->with(['user', 'fromRegion', 'toRegion'])
            ->where('to_region_id', $region->id)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('destinations.show', [
            'region' => $region,
            'apps' => $apps,
        ]);
    }
}
