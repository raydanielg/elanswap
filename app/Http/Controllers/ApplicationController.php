<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Region;
use App\Models\District;
use App\Models\ExchangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationController extends Controller
{
    // Page: My Application
    public function index()
    {
        return view('applications.index');
    }

    // Page: Create (two-step)
    public function create()
    {
        $user = auth()->user();
        return view('applications.create', compact('user'));
    }

    // Store application
    public function store(Request $request)
    {
        try {
            \Log::info('APPLICATION: store() invoked', [
                'user_id' => auth()->id(),
                'payload' => $request->only(['to_region_id','to_district_id','reason'])
            ]);

            $user = auth()->user();
            if (!$user) {
                return redirect()->route('login');
            }

            $data = $request->validate([
                'to_region_id'   => ['required','integer','exists:regions,id'],
                'to_district_id' => ['required','integer','exists:districts,id'],
                'reason'         => ['nullable','string','max:2000'],
            ]);

            $app = Application::create([
                'user_id'         => $user->id,
                'from_region_id'  => $user->region_id,
                'from_district_id'=> $user->district_id,
                'from_station_id' => $user->station_id,
                'to_region_id'    => $data['to_region_id'],
                'to_district_id'  => $data['to_district_id'],
                'reason'          => $data['reason'] ?? null,
                'status'          => 'pending',
                'submitted_at'    => now(),
            ]);

            // Generate tracking code e.g., ELS0023
            if (empty($app->code)) {
                $app->code = 'ELS' . str_pad((string) $app->id, 4, '0', STR_PAD_LEFT);
                $app->save();
            }

            \Log::info('APPLICATION: created', ['id' => $app->id, 'code' => $app->code]);

            return redirect()->route('applications.index')
                ->with('status', 'Application submitted successfully.');
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::warning('APPLICATION: validation failed', [
                'errors' => $ve->errors(),
            ]);
            throw $ve; // Let Laravel redirect back with errors
        } catch (\Throwable $e) {
            \Log::error('APPLICATION: store() failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->withInput()->withErrors([
                'general' => 'Imeshindikana kuhifadhi maombi kwa sasa. Tafadhali jaribu tena.',
            ]);
        }
    }

    // AJAX: search and list application requests
    public function search(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $perPage = (int) $request->query('per_page', 10);
        $perPage = $perPage > 0 && $perPage <= 100 ? $perPage : 10;

        $query = Application::query()
            ->with(['user','fromRegion','fromDistrict','toRegion','toDistrict'])
            ->where('user_id', $request->user()->id)
            ->latest();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('reason', 'like', "%$q%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%");
                    })
                    ->orWhereHas('toRegion', function ($r) use ($q) { $r->where('name','like',"%$q%"); })
                    ->orWhereHas('toDistrict', function ($d) use ($q) { $d->where('name','like',"%$q%"); });
            });
        }

        $paginator = $query->paginate($perPage);

        $items = $paginator->getCollection()->map(function ($app) {
            return [
                'id' => $app->id,
                'code' => $app->code,
                'user' => optional($app->user)->name ?? '—',
                'from' => (string) (optional($app->fromRegion)->name ?? ''),
                'to' => (string) (optional($app->toRegion)->name ?? ''),
                'status' => $app->status,
                'status_label' => $app->status === 'pending' ? 'Received' : ucfirst((string) $app->status),
                'date' => optional($app->submitted_at ?? $app->created_at)->format('d M Y, H:i'),
                'show_url' => route('applications.show', $app->id),
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    // Page: Show single application
    public function show(Application $application)
    {
        $application->load(['user','fromRegion','fromDistrict','fromStation','toRegion','toDistrict']);

        // Access control: owner can view; otherwise only a requester with an accepted exchange
        $user = auth()->user();
        if ($user) {
            // Admin roles can view any application
            if (in_array($user->role ?? 'user', ['admin','superadmin'], true)) {
                // allow
            } else {
                $isOwner = ($application->user_id === $user->id);
                if (!$isOwner) {
                    $hasAcceptedExchange = ExchangeRequest::where('application_id', $application->id)
                        ->where('requester_id', $user->id)
                        ->where('status', 'accepted')
                        ->exists();
                    abort_unless($hasAcceptedExchange, 403);
                }
            }
        } else {
            abort(403);
        }

        // Simple matches for display
        $matches = Application::query()
            ->with(['user','fromRegion','toRegion'])
            ->where('id', '!=', $application->id)
            ->where('status', 'pending')
            ->where('from_region_id', $application->to_region_id)
            ->where('to_region_id', $application->from_region_id)
            ->limit(5)
            ->get();

        $incoming = collect();
        if (auth()->check() && auth()->id() === $application->user_id) {
            $incoming = ExchangeRequest::with([
                'requester',
                'requesterApplication.fromRegion',
                'requesterApplication.toRegion',
            ])
            ->where('application_id', $application->id)
            ->latest('id')
            ->get();
        }

        return view('applications.show', [
            'application' => $application,
            'matches' => $matches,
            'incoming' => $incoming,
        ]);
    }

    // Admin/owner/requester compact preview for modal
    public function peek(Application $application)
    {
        // Load minimal relations
        $application->load(['user','fromRegion','fromDistrict','fromStation','toRegion','toDistrict']);

        // Same access rules as show(): allow admins universally
        $user = auth()->user();
        if ($user) {
            if (!in_array($user->role ?? 'user', ['admin','superadmin'], true)) {
                $isOwner = ($application->user_id === $user->id);
                if (!$isOwner) {
                    $hasAcceptedExchange = ExchangeRequest::where('application_id', $application->id)
                        ->where('requester_id', $user->id)
                        ->where('status', 'accepted')
                        ->exists();
                    abort_unless($hasAcceptedExchange, 403);
                }
            }
        } else {
            abort(403);
        }

        return view('admin.requests._application_peek', [
            'application' => $application,
        ]);
    }

    // JSON: single application details + simple match candidates
    public function details(Application $application)
    {
        $this->authorizeOwner($application);
        $application->load(['user','fromRegion','fromDistrict','fromStation','toRegion','toDistrict']);

        // Very simple match: opposite direction pending requests
        $matches = Application::query()
            ->with(['user','fromRegion','toRegion'])
            ->where('id', '!=', $application->id)
            ->where('status', 'pending')
            ->where('from_region_id', $application->to_region_id)
            ->where('to_region_id', $application->from_region_id)
            ->limit(5)
            ->get()
            ->map(function($m){
                return [
                    'id' => $m->id,
                    'user' => optional($m->user)->name,
                    'from' => optional($m->fromRegion)->name,
                    'to' => optional($m->toRegion)->name,
                ];
            });

        return response()->json([
            'data' => [
                'id' => $application->id,
                'user' => [
                    'name' => optional($application->user)->name,
                    'phone' => optional($application->user)->phone,
                ],
                'from_region' => optional($application->fromRegion)->name,
                'from_district' => optional($application->fromDistrict)->name,
                'from_station' => optional($application->fromStation)->name,
                'to_region' => optional($application->toRegion)->name,
                'to_district' => optional($application->toDistrict)->name,
                'reason' => $application->reason,
                'status' => $application->status,
                'status_label' => $application->status === 'pending' ? 'Received' : ucfirst((string) $application->status),
                'date' => optional($application->submitted_at ?? $application->created_at)->format('d M Y, H:i'),
            ],
            'matches' => $matches,
        ]);
    }

    public function approve(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        // Disallow approving without explicit match selection
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Select a match to approve with'], 422);
        }
        return redirect()->route('applications.show', $application)
            ->with('error', 'Select a match to approve with');
    }

    public function reject(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        if ($application->status !== 'pending') {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Already processed'], 422);
            }
            return redirect()->back()->with('error', 'Already processed');
        }
        $application->status = 'rejected';
        $application->save();
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Rejected', 'status' => 'rejected']);
        }
        return redirect()->route('applications.show', $application)->with('status', 'Rejected');
    }

    public function requestDeletion(Request $request, Application $application)
    {
        $this->authorizeOwner($application);
        if ($application->status === 'deletion_requested') {
            return redirect()->back()->with('status', 'Deletion already requested');
        }
        $application->status = 'deletion_requested';
        $application->save();
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Deletion requested', 'status' => 'deletion_requested']);
        }
        return redirect()->route('applications.show', $application)->with('status', 'Deletion requested');
    }

    // Approve current application with a specific matching application and auto-reject others
    public function approveWithMatch(Request $request, Application $application, Application $match)
    {
        $this->authorizeOwner($application);
        // Validate both pending
        if ($application->status !== 'pending' || $match->status !== 'pending') {
            return redirect()->back()->with('error', 'One of the applications is already processed');
        }
        // Validate that they are opposite-direction match
        $isMatch = ($application->from_region_id === $match->to_region_id)
            && ($application->to_region_id === $match->from_region_id);
        if (!$isMatch) {
            return redirect()->back()->with('error', 'Invalid match selection');
        }

        // Approve both and pair them
        $application->status = 'approved';
        $application->paired_application_id = $match->id;
        $application->save();

        $match->status = 'approved';
        $match->paired_application_id = $application->id;
        $match->save();

        // Auto-reject other pending matches for BOTH directions
        // Others that could match $application (except $match)
        Application::query()
            ->where('id', '!=', $match->id)
            ->where('status', 'pending')
            ->where('from_region_id', $application->to_region_id)
            ->where('to_region_id', $application->from_region_id)
            ->update(['status' => 'rejected']);

        // Others that could match $match (except $application)
        Application::query()
            ->where('id', '!=', $application->id)
            ->where('status', 'pending')
            ->where('from_region_id', $match->to_region_id)
            ->where('to_region_id', $match->from_region_id)
            ->update(['status' => 'rejected']);

        return redirect()->route('applications.show', $application)
            ->with('status', 'Approved with selected match');
    }

    private function authorizeOwner(Application $application): void
    {
        $user = auth()->user();
        abort_unless($application->user_id === $user->id, 403);
    }
}
