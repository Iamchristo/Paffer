<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfInstalled
{
    /**
     * Keep the installer from running again once the platform is set up.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_file(storage_path('installed'))) {
            return redirect('/');
        }

        return $next($request);
    }
}
