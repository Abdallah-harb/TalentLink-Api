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
        // search filters
    public function bestJobs(Request $request){
        try {
            $majors = $request->job_majors; // التخصصات
            $province_name = $request->provence_name;// المحافظات
            $province_id = $request->province_id;
            $cityName = $request->city_name;
            $city_id = $request->ity_id;
            $job_type = $request->job_type;
            $job_level = $request->job_level;
            $minExp = $request->min_exp;
            $maxExp = $request->max_exp;
            $job_date = $request->job_date;


            $search =Job::with('majors');

            //search depend on majors
            if(isset($majors)){
                $search =  $search->whereHas('majors', function ($q) use ($majors) {
                    $q->whereIn('majors.id', $majors);
                });
            }
            // search depend on province name or province id
            if(isset($province_name)){
                $search = $search->whereHas('province.translations',function ($q) use ($province_name){
                    $q->where('name','like','%'.$province_name.'%');
                })->with('province');
            }
            // search depend on province id
            if(isset($province_id)){
                $search = $search->whereHas('province.translations',function ($q) use ($province_id){
                    $q->whereIn('province_id',$province_id);
                })->with('province');
            }

            //search depend on cityName
            if(isset($cityName)){
                $search = $search->whereHas('city.translations',function ($q) use($cityName){
                    $q->where('name','like','%'.$cityName.'%');
                })->with('city');
            }

            //search depend on city_id
            if(isset($city_id)){
                $search = $search->whereHas('city.translations',function ($q) use($city_id){
                    $q->whereIn('city_id',$city_id);
                })->with('city');
            }

            //search depend on job_type
            if(isset($job_type)){
                $search = $search->where(function ($q) use ($job_type){
                    foreach ($job_type as $type){
                        $q->orWhere('job_type',$type);
                    }
                });
            }

            //search depend on job_level
            if(isset($job_level)){
                $search = $search->where(function($q) use ($job_level){
                    foreach ($job_level as $value){
                        $q->orWhere('job_level',$value);
                    }
                });
            }
            //search depend on minExp
            if(isset($minExp)){
                $search = $search->where('start_year','like','%'.$minExp.'%');
            }
            //search depend on minExp
            if(isset($maxExp)){
                $search = $search->where('end_year','like','%'.$maxExp.'%');
            }

            /* return Carbon::now()->subWeek(); // data in last weeks

             return [Carbon::today(),Carbon::now()->subWeek(),Carbon::now()->subMonth(),Carbon::now()->subMonth()->month,date('Y-M-D H:M:S')];

             */

            //search depend on data of jobs
            if(isset($job_date)){
                //last 24 hours jobs
                if($job_date == "last_24_hours"){
                    $search = $search->whereBetween('created_at',[Carbon::today(),Carbon::now()])
                        ->orWhereBetween('updated_at',[Carbon::today(),Carbon::now()]);
                }
                //last week's jobs
                if($job_date == "last_week"){
                    $search = $search->whereBetween('created_at',[Carbon::now()->subWeek(),Carbon::now()])
                        ->orWhereBetween('updated_at',[Carbon::now()->subWeek(),Carbon::now()]);
                }
                //last month jobs
                if($job_date == "last_month"){
                    $search = $search->whereBetween('created_at',[Carbon::now()->subMonth(),Carbon::now()])
                        ->orWhereBetween('updated_at',[Carbon::now()->subMonth(),Carbon::now()]);
                }
                //all jobs
                if($job_date == "all"){
                    $search = $search;
                }
            }

            $search = $search->orderByDesc('id')->get();

            if ($search->isEmpty()) {
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are No results  jobs ');
            }
            return ResponseHelper::sendResponseSuccess(JobResource::collection($search));
        } catch (\Exception $ex) {
            return  ResponseHelper::sendResponseError([], Response::HTTP_BAD_REQUEST, $ex->getMessage());
        }

        /*try {
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
        }*/

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


