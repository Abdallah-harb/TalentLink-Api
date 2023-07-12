<?php

namespace App\Http\Middleware;

use App\Http\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;

class Jop_Seeker
{

    public function handle(Request $request, Closure $next): Response
    {
        if(auth('api')->user()->type != 'job_seeker') {

          return ResponseHelper::sendResponseError([],\Illuminate\Http\Response::HTTP_BAD_REQUEST,'this pages for job seeker only');
        }
        return $next($request);
    }

}
