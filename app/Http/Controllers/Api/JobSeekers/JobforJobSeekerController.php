<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\HelperFile;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\JopSeeker\JobEnrollRequest;
use App\Http\Resources\Company\JobResource;
use App\Models\Job;
use App\Models\PreviousJobsForPreviousExperiences;
use App\Models\UserJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class JobforJobSeekerController extends Controller
{

    public function index()
    {
        try {
            $jobs = Job::with('majors')->orderByDesc('id')->get();
            return ResponseHelper::sendResponseSuccess(JobResource::collection($jobs));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }

    public function bestJobs(Request $request){
        try {
            $userprevjobs = PreviousJobsForPreviousExperiences::whereUserId(auth('api')->user()->id)
                ->pluck('Job_name');
            $bestjobs = Job::with('majors')
                ->whereIn('job_title', function ($query) use ($userprevjobs) {
                    $query->select('job_title')
                        ->from('jobs');
                    foreach ($userprevjobs as $job) {
                        $query->orWhere('job_title', 'like', '%' . $job . '%');
                    }
                })->get();

            //search filter for best job
            //Job classification
            $Job_classification = $request->input('Job_classification', []);
            $city = $request->input('city', []);
            $job_type = $request->input('job_type', []);
            $job_level = $request->input('job_level', []);
            $minExp = $request->input('minExp');
            $maxExp = $request->input('maxExp');
            $publish_data = $request->input('publish_data');
            if(isset($publish_data)){
                $publish_data = match ($publish_data) {
                    'today' => Carbon::today(),
                    'lastWeek' => Carbon::now()->subWeek(),
                    'last_month' => Carbon::now()->subMonth()->month,
                    'all' => date('Y-M-D H:M:S'),
                };
            }
            $job = Job::whereHas('majors');
            if(!empty($Job_classification)){
               $job = $job->whereHas('majors', function ($q) use ($Job_classification) {
                    $q->whereIn('majors.id', $Job_classification);
                });
            }
            if(!empty($city)){
                $job = $job->where(function ($q) use ($city) {
                    foreach ($city as $c) {
                        $q->orWhere('city_id', $c);
                    }
                });
            }
            if(!empty($job_type)){
                $job = $job->where(function ($q) use ($job_type) {
                    foreach ($job_type as $jt) {
                        $q->orWhere('job_type', $jt);
                    }
                });
            }
            if(!empty($job_level)){
                $job = $job->where(function ($q) use ($job_level) {
                    foreach ($job_level as $jl) {
                        $q->orWhere('job_level', $jl);
                    }
                });
            }
            if(!empty($minExp)){
                $job = $job->where(function ($q) use ($minExp) {
                        $q->where('start_year', $minExp); });
            }
            if(!empty($maxExp)){
                $job = $job->where(function ($q) use ($maxExp) {
                    $q->where('end_year', $maxExp); });
            }
            if(!empty($publish_data)){
                $job = $job->where(function ($q) use ($publish_data) {
                    if ($publish_data instanceof Carbon) {
                        $q->whereBetween('created_at', [$publish_data->startOfDay(), $publish_data->endOfDay()]);
                    }
                });
            }
            $job = $job->orderByDesc('id')->get();

            if(!empty($Job_classification) OR !empty($city) OR !empty($job_type) OR !empty($job_level)  OR !empty($minExp) OR !empty($maxExp) OR !empty($publish_data) ){
                return ResponseHelper::sendResponseSuccess(JobResource::collection($job));
            }else{
                return ResponseHelper::sendResponseSuccess(JobResource::collection($bestjobs));
            }

        } catch (\Exception $ex) {
            return  ResponseHelper::sendResponseError([], Response::HTTP_BAD_REQUEST, $ex->getMessage());
        }

    }


    public function show(string $id)
    {
        try {
            $job = Job::with('majors')->whereId($id)->get();
            if(count($job) < 1){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on data');
            }
            return ResponseHelper::sendResponseSuccess(JobResource::collection($job));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function store(JobEnrollRequest $request,$id){
        try {
            if(UserJob::whereUserId(auth('api')->user()->id)->whereJobId($id)->exists()){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'You are already enrolled in');
            }
           $cv =  HelperFile::uploadImage($request->cv,'cv');
            $enrolljob = UserJob::create([
                "job_id" => $id,
                "user_id" => auth('api')->user()->id,
                "cv" => $cv
            ]);
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"You are Enroll in Job Successfully");
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }






}


