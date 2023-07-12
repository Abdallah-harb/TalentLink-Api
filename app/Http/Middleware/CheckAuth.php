<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Helper\ResponseHelper;

class CheckAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('api')->check()) {

            return ResponseHelper::sendResponseError([], \Illuminate\Http\Response::HTTP_BAD_REQUEST ,  __("messages_1.Need To Login"));
        }

        return $next($request);
    }
}
