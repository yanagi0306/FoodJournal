<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\Store;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShareAuthenticatedUser
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user) {
            $company = Company::find($user->company_id);
            $store = Store::find($user->store_id);

            $userArray = $user->toArray();
            $userArray['store_name'] = $store?->store_name;
            $userArray['company_name'] = $company?->company_name;

            Inertia::share([
                'auth' => [
                    'user' => $userArray,
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
