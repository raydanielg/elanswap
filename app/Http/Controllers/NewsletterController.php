<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc,dns', 'max:255'],
        ]);

        // Create or ignore if already subscribed
        NewsletterSubscription::firstOrCreate(
            ['email' => strtolower($validated['email'])],
            [
                'ip_address' => $request->ip(),
                'user_agent' => (string) $request->userAgent(),
            ]
        );

        return back()->with('newsletter_success', 'Umejisajili kwa mafanikio! Asante.');
    }
}
