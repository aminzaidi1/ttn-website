<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Redirect;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /** Simple editable pages (privacy, terms, contact), then saved redirects, then the 404. */
    public function show(Request $request, string $path)
    {
        if ($page = Page::where('slug', $path)->first()) {
            return view('page', ['page' => $page]);
        }

        if ($redirect = Redirect::where('from_path', '/'.trim($path, '/'))->first()) {
            return redirect($redirect->to_path, 301);
        }

        abort(404);
    }
}
