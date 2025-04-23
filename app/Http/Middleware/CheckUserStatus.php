<?php

namespace App\Http\Middleware;

use Closure;

class CheckUserStatus
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if ($user && $user->status === 'inactive') {
            return redirect()->route('inactive')->with('message', 'Your account is inactive.');
        }

        return $next($request);
    }
}
