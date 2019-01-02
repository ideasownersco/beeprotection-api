<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Admin
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user->active && $user->admin) {
            return $next($request);
        }

        return response()->json(
            ['success' => false, 'message' => trans('general.invalid_request'), 'type' => 'invalid_request'],
            403
        );
    }
}
