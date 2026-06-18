<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    /**
     * Send every request to the installer until storage/installed exists.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            ! app()->environment('testing')
            && ! $request->is('install*')
            && ! is_file(storage_path('installed'))
        ) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }
}
