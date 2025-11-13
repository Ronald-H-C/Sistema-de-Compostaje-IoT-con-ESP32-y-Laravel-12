<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SaveLastUrl
{
    public function handle(Request $request, Closure $next)
    {
        // Solo guardamos si NO está autenticado, es GET y no es login/registro
        if (!auth()->check() && $request->method() === 'GET' && !$request->is('login', 'registro')) {
            session(['last_url' => $request->fullUrl()]);
        }

        return $next($request);
    }
}
