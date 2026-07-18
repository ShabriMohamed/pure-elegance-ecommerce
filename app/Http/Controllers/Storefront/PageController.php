<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\PageContent;

class PageController extends Controller
{
    /**
     * Render a CMS content page. 404s on unknown or inactive slugs
     * (previously this route rendered a non-existent view → HTTP 500).
     */
    public function show(string $slug)
    {
        $page = PageContent::active()->where('slug', $slug)->firstOrFail();

        return view('storefront.page', compact('page'));
    }
}
