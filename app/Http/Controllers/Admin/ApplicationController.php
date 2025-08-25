<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = (string) $request->get('status', ''); // pending, accepted, rejected
        $exchanged = (string) $request->get('exchanged', ''); // '', 'yes', 'no'

        $apps = Application::query()
            ->with(['user','fromRegion','toRegion'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('code', 'like', "%$q%")
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%$q%"));
                });
            })
            ->when(in_array($status, ['pending','accepted','rejected'], true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when(in_array($exchanged, ['yes','no'], true), function ($query) use ($exchanged) {
                if ($exchanged === 'yes') {
                    $query->whereNotNull('paired_application_id');
                } else {
                    $query->whereNull('paired_application_id');
                }
            })
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'total' => Application::count(),
            'pending' => Application::where('status','pending')->count(),
            'accepted' => Application::where('status','accepted')->count(),
            'rejected' => Application::where('status','rejected')->count(),
        ];

        return view('admin.applications.index', compact('apps','q','status','exchanged','counts'));
    }

    public function destroy(Application $application)
    {
        $application->delete();
        return redirect()->route('admin.applications.index')
            ->with('success', 'Application deleted successfully.');
    }

    public function peek(Application $application)
    {
        // Minimal relationships only
        $application->loadMissing(['user','fromRegion','toRegion','pairedApplication.user']);

        return view('admin.applications._peek', [
            'application' => $application,
        ]);
    }
}
