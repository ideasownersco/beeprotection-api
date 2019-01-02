<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Customer
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if ($user->admin || $user->active && $user->type === 0) {
            return $next($request);
        }

        return response()->json(
            ['success' => false, 'message' => trans('general.invalid_request'), 'type' => 'invalid_request'],
            403
        );
    }
}
