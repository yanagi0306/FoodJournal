<?php

namespace App\Http\Middleware;

use App\Helpers\UserHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShareAuthenticatedUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = UserHelper::getUser(Auth::user());

        if ($user) {

            Inertia::share([
                'auth' => [
                    'user' => $user,
                ],
            ]);
        } else {
            Inertia::share([
                'auth' => [],
            ]);
        }


        return $next($request);
    }
}
