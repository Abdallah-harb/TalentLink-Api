<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\Company\JobResource;use Illuminate\Http\Request;use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class FavJobController extends Controller
{

    public function index()
    {
        try {
          $JobsFav = auth('api')->user()->favJobs()->latest()->get();
              if(count($JobsFav) < 1){
                  return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Job added To Fav Yet');
              }
              return ResponseHelper::sendResponseSuccess(JobResource::collection($JobsFav));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            if(! auth('api')->user()->favJobsHas(request('job_id'))){
                //if it not exists on bd  added
                auth('api')->user()->favJobs()->attach(request('job_id'));

                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"job added to fav");
            }
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_BAD_REQUEST,"this job already exists");
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }


    public function destroy()
    {
        try {

            $jobId = request('job_id');
            auth('api')->user()->favJobs()->detach($jobId);
                 return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"job delete from fav");

        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
