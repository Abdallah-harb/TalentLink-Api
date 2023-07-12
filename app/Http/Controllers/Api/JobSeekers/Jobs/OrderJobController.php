<?php

namespace App\Http\Controllers\Api\JobSeekers\Jobs;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\JopSeeker\Jobs\OrderJobRequest;
use App\Http\Resources\JopSeeker\Job\JobOrderResource;
use App\Models\JobOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderJobController extends Controller
{

    public function index()
    {
        try {
            $allOrderJob = JobOrder::whereUserId(auth('api')->user()->id)->get();
            if(count($allOrderJob) < 1){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are no Jobs You Published Yet');
            }
            return ResponseHelper::sendResponseSuccess(JobOrderResource::collection($allOrderJob));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }


    public function store(OrderJobRequest $request)
    {
        try {
            $technical_words = implode(',',$request->technical_words);
            $personal_skills = implode(',',$request->personal_skills);
            $orderJob = JobOrder::create([
                "job_title" => $request->job_title,
                "job_type" => $request->job_type,
                "major_id" => $request->major_id,
                "province_id" => $request->province_id,
                "city_id" => $request->city_id,
                "job_level" => $request->job_level,
                "start_year" =>$request->start_year,
                "end_year" =>$request->end_year,
                "start_salary" => $request->start_salary,
                "end_salary" => $request->end_salary,
                "agreement_with_employee" =>$request->agreement_with_employee,
                "technical_words" => $technical_words,
                "personal_skills" => $personal_skills,
                "user_id"=>auth('api')->user()->id
            ]);
            return ResponseHelper::sendResponseSuccess(new JobOrderResource($orderJob));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function show(string $id)
    {
        try {
            $orderJob = JobOrder::whereUserId(auth('api')->user()->id)->whereId($id)->get();
            if(count($orderJob) < 1){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are error on data');
            }
            return ResponseHelper::sendResponseSuccess(JobOrderResource::collection($orderJob));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function update(OrderJobRequest $request, string $id)
    {
        try {
            $orderJob = JobOrder::whereUserId(auth('api')->user()->id)->find($id);
            if(!$orderJob){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are error on data');
            }
            $technical_words = implode(',',$request->technical_words);
            $personal_skills = implode(',',$request->personal_skills);
            $orderJob->update([
                "job_title" => $request->job_title,
                "job_type" => $request->job_type,
                "major_id" => $request->major_id,
                "province_id" => $request->province_id,
                "city_id" => $request->city_id,
                "job_level" => $request->job_level,
                "start_year" =>$request->start_year,
                "end_year" =>$request->end_year,
                "start_salary" => $request->start_salary,
                "end_salary" => $request->end_salary,
                "agreement_with_employee" =>$request->agreement_with_employee,
                "technical_words" => $technical_words,
                "personal_skills" => $personal_skills
            ]);
            return ResponseHelper::sendResponseSuccess(new JobOrderResource($orderJob));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function destroy(string $id)
    {
        try {
            $orderJob = JobOrder::whereUserId(auth('api')->user()->id)->find($id);
            if(!$orderJob){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'There are error on data');
            }
            $orderJob->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'Data delete Successfully');
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
