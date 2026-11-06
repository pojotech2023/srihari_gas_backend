<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Show the Contact page.
     */
    public function show()
    {
        $site = config('site');

        return view('pages.contact', [
            'brand' => $site['brand'],
        ]);
    }

    /**
     * Handle contact form submission.
     */
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|min:10|max:3000',
        ]);

        try {
            ContactMessage::create($validated);
        } catch (\Throwable $e) {
            // Graceful logging if database table is pending migration or connection issue
            Log::warning('Contact message could not be stored in DB: ' . $e->getMessage(), $validated);
        }

        $successMsg = 'Thank you! Your message has been received. Our team will contact you shortly.';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
            ]);
        }

        return redirect()->route('site.contact')->with('success', $successMsg);
    }
}
