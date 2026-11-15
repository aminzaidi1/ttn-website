<?php

namespace App\Http\Controllers;

use App\Models\AboutPhoto;
use App\Models\Photo;
use App\Models\TeamMember;

class AboutController extends Controller
{
    public function __invoke()
    {
        return view('about', [
            'portrait' => Photo::slot('FOUNDER-01'),
            'gallery' => AboutPhoto::with('photo')->orderBy('order')->get(),
            'team' => TeamMember::with('photo')->orderBy('order')->get(),
        ]);
    }
}
