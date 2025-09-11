<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Region;
use App\Models\ExchangeRequest;
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
            ->with(['user.category', 'fromRegion', 'toRegion'])
            ->where('to_region_id', $region->id)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $myPendingApps = Application::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->get();

        // Map of app_id => ['status' => ..., 'id' => ...] for user's requests on these apps
        $requestedMap = [];
        if ($request->user()) {
            $ids = $apps->getCollection()->pluck('id');
            if ($ids->isNotEmpty()) {
                $existing = ExchangeRequest::query()
                    ->select(['id','application_id','status'])
                    ->whereIn('application_id', $ids)
                    ->where('requester_id', $request->user()->id)
                    ->latest('id')
                    ->get();
                foreach ($existing as $er) {
                    $requestedMap[$er->application_id] = ['status' => $er->status, 'id' => $er->id];
                }
            }
        }

        return view('destinations.show', [
            'region' => $region,
            'apps' => $apps,
            'myPendingApps' => $myPendingApps,
            'requestedMap' => $requestedMap,
        ]);
    }
}
