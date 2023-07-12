<?php

namespace App\Http\Middleware;

use App\Http\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class campanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(auth('api')->user()->type !='company'){
            return  ResponseHelper::sendResponseError([],\Illuminate\Http\Response::HTTP_BAD_REQUEST,'This pages for company only');
        }
        return $next($request);
    }
}
