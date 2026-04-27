<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || $request->user()->type !== 'admin') {
            return response()->json([
                'message' => 'Unauthorized - Admins only'
            ]);
        }

        return $next($request);
    }
}