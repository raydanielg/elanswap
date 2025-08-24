<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\ExchangeRequest;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExchangeRequestController extends Controller
{
    public function index(Request $request)
    {
        $requests = ExchangeRequest::with(['application.fromRegion', 'application.toRegion', 'owner'])
            ->where('requester_id', $request->user()->id)
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        $hasApproved = (clone $requests)->getCollection()->contains(fn($r) => $r->status === 'accepted');
        $hasRejected = (clone $requests)->getCollection()->contains(fn($r) => $r->status === 'rejected');

        return view('requests.index', compact('requests', 'hasApproved', 'hasRejected'));
    }

    public function show(Request $request, ExchangeRequest $requestModel)
    {
        abort_unless($requestModel->requester_id === $request->user()->id, 403);
        $requestModel->load(['application.fromRegion', 'application.toRegion', 'owner', 'requester', 'requesterApplication.fromRegion', 'requesterApplication.toRegion']);
        return view('requests.show', ['req' => $requestModel]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'requester_application_id' => ['nullable', 'exists:applications,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $targetApp = Application::with('user')->findOrFail($data['application_id']);

        // Prevent self-request
        if ($targetApp->user_id === Auth::id()) {
            return back()->with('error', 'You cannot request exchange on your own application.');
        }

        // Ensure requester_application_id (if provided) belongs to the requester and is pending
        if (!empty($data['requester_application_id'])) {
            $reqApp = Application::where('id', $data['requester_application_id'])
                ->where('user_id', Auth::id())
                ->where('status', 'pending')
                ->first();
            if (!$reqApp) {
                return back()->with('error', 'Invalid selection of your application.');
            }
        }

        // Create or get existing pending request to avoid duplicates
        $requestModel = ExchangeRequest::firstOrCreate([
            'requester_id' => Auth::id(),
            'application_id' => $targetApp->id,
        ], [
            'owner_id' => $targetApp->user_id,
            'requester_application_id' => $data['requester_application_id'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => 'pending',
        ]);

        // Simple notifications via Log entries
        try {
            Log::create([
                'user_id' => $targetApp->user_id,
                'log_type' => 'notification',
                'status' => 'new',
                'text' => 'New exchange request received for application #' . $targetApp->id,
                'user_agent' => json_encode(['type' => 'exchange_request', 'id' => $requestModel->id]),
            ]);

            Log::create([
                'user_id' => Auth::id(),
                'log_type' => 'notification',
                'status' => 'sent',
                'text' => 'Exchange request sent for application #' . $targetApp->id,
                'user_agent' => json_encode(['type' => 'exchange_request', 'id' => $requestModel->id]),
            ]);
        } catch (\Throwable $e) {
            // ignore logging errors
        }

        return back()->with('status', 'Exchange request sent.');
    }
}
