<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Asumsi level admin adalah 1, sesuaikan dengan database jika diperlukan
        if (Auth::check() && Auth::user()->level == 'admin') {
            return $next($request);
        }

        return redirect('/'); // Redirect jika bukan admin
    }
}
