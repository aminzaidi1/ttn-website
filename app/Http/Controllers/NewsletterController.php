<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/** Stores newsletter subscribers. Nothing is sent yet: the list is the asset. */
class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        // Honeypot: real people never fill the hidden field. Pretend success so bots learn nothing.
        $bot = filled($request->input('website'));

        $valid = Validator::make($request->all(), ['email' => ['required', 'email:rfc', 'max:190']])->passes();

        if ($valid && ! $bot) {
            Subscriber::firstOrCreate(
                ['email' => Str::lower(trim($request->input('email')))],
                ['source' => Str::limit((string) $request->input('source', 'site'), 40, '')]
            );
        }

        if ($request->expectsJson()) {
            return $valid ? response()->json(['ok' => true]) : response()->json(['ok' => false], 422);
        }

        $back = strtok(url()->previous(url('/')), '?#');

        return redirect()->to($back.'?subscribed='.($valid ? '1' : '0').'#newsletter');
    }
}
