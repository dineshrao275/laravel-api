<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\BaseController;
use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission)
    {
         $user = $request->user();

        if (!$user || !$user->hasPermission($permission)) {
            return  BaseController::res('You do not have permission to access this resource', false, [], 401);
        }

        return $next($request);
    }
}
