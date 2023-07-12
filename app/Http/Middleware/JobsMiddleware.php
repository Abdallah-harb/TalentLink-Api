<?php

namespace App\Http\Middleware;

use App\Http\Helper\ResponseHelper;
use App\Models\UserPrograme;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userCourse = UserPrograme::whereUserId(auth('api')->user()->id)->first();
        if(!$userCourse){
            return ResponseHelper::sendResponseError([],\Illuminate\Http\Response::HTTP_BAD_REQUEST,"You are not enrolled the course yet");
        }
        if($userCourse->status === 'enrolled'){
            return ResponseHelper::sendResponseError([], \Illuminate\Http\Response::HTTP_BAD_REQUEST,"You have not completed the course yet");
        }
        return $next($request);
    }
}
