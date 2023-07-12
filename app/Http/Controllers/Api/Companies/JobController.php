<?php

namespace App\Http\Controllers\Api\Companies;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\Company\JobCompanyRequest;
use App\Http\Resources\Company\JobResource;
use App\Models\Job;
use App\Models\Notification;
use App\Models\UserJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allJobs = Job::whereUserId(auth('api')->user()->id)->get();
        if(count($allJobs) <1){
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"No Jobs Yet");

        }
        return ResponseHelper::sendResponseSuccess(JobResource::collection($allJobs));
    }


    public function store(JobCompanyRequest $request)
    {
        DB::beginTransaction();
        try {
            $check = Job::whereUserId(auth('api')->user()->id)->whereJobTitle($request->job_title)->exists();
            if($check){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"You Publish This Job lately");
            }
            $technicalSkills = implode(',', $request->input(['technical_words']));
            $personalSkills = implode(',', $request->input(['personal_skills']));
            $job = Job::create([
                "job_title" => $request->job_title,
                "job_type" => $request->job_type,
                "province_id" =>$request->province_id,
                "city_id" => $request->city_id,
                "job_level" => $request->job_level,
                "start_year" =>$request->start_year,
                "end_year" =>$request->end_year,
                "start_salary" =>$request->start_salary,
                "end_salary" => $request->end_salary,
                "agreement_with_employee" =>$request->agreement_with_employee,
                "start_job_data" => $request->start_job_data,
                "job_description" => $request->job_description,
                "job_requirement" => $request->job_requirement,
                "technical_words" =>$technicalSkills,
                "personal_skills" =>$personalSkills,
                "user_id" => auth('api')->user()->id
            ]);
            //add many majors
            $job->majors()->attach($request->majors);
            DB::commit();
            return ResponseHelper::sendResponseSuccess(new JobResource($job));
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $job = Job::whereId($id)->get();
        if(count($job) < 1){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"there are error on data");
        }
        return ResponseHelper::sendResponseSuccess(JobResource::collection($job));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JobCompanyRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $job = Job::whereUserId(auth('api')->user()->id)->whereId($id)->find($id);
            if(!$job){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are in data');
            }
            $technicalSkills = implode(',', $request->input(['technical_words']));
            $personalSkills = implode(',', $request->input(['personal_skills']));
            $job->update([
                "job_title" => $request->job_title,
                "job_type" => $request->job_type,
                "province_id" =>$request->province_id,
                "city_id" => $request->city_id,
                "job_level" => $request->job_level,
                "start_year" =>$request->start_year,
                "end_year" =>$request->end_year,
                "start_salary" =>$request->start_salary,
                "end_salary" => $request->end_salary,
                "agreement_with_employee" =>$request->agreement_with_employee,
                "start_job_data" => $request->start_job_data,
                "job_description" => $request->job_description,
                "job_requirement" => $request->job_requirement,
                "technical_words" =>$technicalSkills,
                "personal_skills" =>$personalSkills,
                "user_id" => auth('api')->user()->id
            ]);
            $job->majors()->sync($request->majors);
            DB::commit();
            return ResponseHelper::sendResponseSuccess(new JobResource($job));
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $job = Job::whereUserId(auth('api')->user()->id)->find($id);
            if(!$job){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are in data');
            }
            $job->majors()->detach();

            $job->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'Job delete successfully');
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }
    }
}
