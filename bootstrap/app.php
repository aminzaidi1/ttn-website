<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // The newsletter form sits on cached pages, so it cannot carry a per-visitor token.
        // It is protected by a honeypot field and rate limiting instead.
        $middleware->validateCsrfTokens(except: ['newsletter']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Before showing a 404, check whether an editor renamed this page: old addresses keep working.
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, Request $request) {
            if ($request->isMethod('GET') && ! $request->is('admin*')) {
                try {
                    $redirect = \App\Models\Redirect::where('from_path', '/'.trim($request->path(), '/'))->first();
                } catch (\Throwable) {
                    $redirect = null;
                }
                if ($redirect) {
                    return redirect($redirect->to_path, 301);
                }
            }
        });
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
