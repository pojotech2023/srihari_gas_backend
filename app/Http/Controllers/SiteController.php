<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiteController extends Controller
{
    /**
     * Show the Home page.
     */
    public function home()
    {
        $site = config('site');

        return view('pages.home', [
            'brand' => $site['brand'],
            'stats' => $site['stats'],
            'cylinders' => array_slice($site['cylinders'], 0, 3),
            'why_choose_us' => $site['why_choose_us'],
            'testimonials' => $site['testimonials'],
            'faqs' => array_slice($site['faqs'], 0, 4),
        ]);
    }

    /**
     * Show the About page.
     */
    public function about()
    {
        $site = config('site');

        return view('pages.about', [
            'brand' => $site['brand'],
            'stats' => $site['stats'],
        ]);
    }

    /**
     * Show the Services page.
     */
    public function services()
    {
        $site = config('site');

        return view('pages.services', [
            'brand' => $site['brand'],
            'cylinders' => $site['cylinders'],
            'faqs' => $site['faqs'],
        ]);
    }
}
