<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Untuk permintaan API, kembalikan respons JSON alih-alih redirect
        if ($request->expectsJson()) {
            abort(response()->json([
                'message' => 'Unauthenticated.',
            ], 401));
        }

        // Untuk permintaan biasa, redirect ke halaman login
        return route('login');
    }
}
