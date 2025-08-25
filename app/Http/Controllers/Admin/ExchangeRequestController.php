<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;

class ExchangeRequestController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q'));
        $status = (string) $request->input('status');

        $requests = ExchangeRequest::query()
            ->with([
                'requester',
                'owner',
                'application.fromRegion', 'application.toRegion',
                'requesterApplication.fromRegion', 'requesterApplication.toRegion',
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->whereHas('requester', fn($u) => $u->where('name', 'like', "%$q%"))
                        ->orWhereHas('owner', fn($u) => $u->where('name', 'like', "%$q%"))
                        ->orWhere('message', 'like', "%$q%")
                        ->orWhereHas('application', fn($app) => $app->where('code', 'like', "%$q%"));
                });
            })
            ->when(in_array($status, ['pending','accepted','rejected'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        if ($request->ajax()) {
            return view('admin.requests._table', [
                'requests' => $requests,
            ]);
        }

        $counts = [
            'pending' => ExchangeRequest::where('status','pending')->count(),
            'accepted' => ExchangeRequest::where('status','accepted')->count(),
            'rejected' => ExchangeRequest::where('status','rejected')->count(),
        ];

        return view('admin.requests.index', [
            'requests' => $requests,
            'q' => $q,
            'status' => $status,
            'counts' => $counts,
        ]);
    }

    public function show(ExchangeRequest $exchangeRequest)
    {
        $exchangeRequest->load([
            'requester', 'owner',
            'application.fromRegion','application.toRegion','application.user',
            'requesterApplication.fromRegion','requesterApplication.toRegion','requesterApplication.user',
            'requester.region','requester.district','requester.category','requester.station',
        ]);
        return view('admin.requests.show', [
            'req' => $exchangeRequest,
        ]);
    }

    public function approve(Request $request, ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->status !== 'pending') {
            return back()->with('error', 'This request is already processed.');
        }
        $exchangeRequest->status = 'accepted';
        $exchangeRequest->save();
        return back()->with('status', 'Request approved.');
    }

    public function reject(Request $request, ExchangeRequest $exchangeRequest)
    {
        if ($exchangeRequest->status !== 'pending') {
            return back()->with('error', 'This request is already processed.');
        }
        $exchangeRequest->status = 'rejected';
        $exchangeRequest->save();
        return back()->with('status', 'Request rejected.');
    }
}
