<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\JopSeeker\ProgrammeRequest;
use App\Http\Resources\Institutes\CourseResource;
use App\Http\Resources\JopSeeker\ProgrammeResource;
use App\Models\Courses;
use App\Models\UserPrograme;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProgrammeController extends Controller
{

    public function index(Request $request)
    {
        try {
            //search filter for best job
            //Job classification
            $course_classification = $request->input('course_classification', []);
            $city = $request->input('city', []);
            $course_type = $request->input('course_type', []);
            $course_level = $request->input('course_level', []);
            $duration = $request->input('duration');
            $publish_data = $request->input('publish_data');
            if(isset($publish_data)){
                $publish_data = match ($publish_data) {
                    'today' => Carbon::today(),
                    'lastWeek' => Carbon::now()->subWeek(),
                    'last_month' => Carbon::now()->subMonth()->month,
                    'all' => date('Y-M-D H:M:S'),
                };
            }
            $allCources = Courses::with(['majors','user:id,first_name'])->where('course_title','!=','البرنامج التأهيلى');

            if(!empty($course_classification)){
                $allCources = $allCources->whereHas('majors', function ($q) use ($course_classification) {
                    $q->whereIn('majors.id', $course_classification);
                });
            }
            if(!empty($city)){
                $allCources = $allCources->where(function ($q) use ($city) {
                    foreach ($city as $c) {
                        $q->orWhere('city_id', $c);
                    }
                });
            }
            if(!empty($course_type)){
                $allCources = $allCources->where(function ($q) use ($course_type) {
                    foreach ($course_type as $jt) {
                        $q->orWhere('course_type', $jt);
                    }
                });
            }
            if(!empty($course_level)){
                $allCources = $allCources->where(function ($q) use ($course_level) {
                    foreach ($course_level as $jl) {
                        $q->orWhere('course_level', $jl);
                    }
                });
            }
            if(!empty($duration)){
                $allCources = $allCources->where(function ($q) use ($duration) {
                    $q->where('duration', $duration); });
            }

            if(!empty($publish_data)){
                $allCources = $allCources->where(function ($q) use ($publish_data) {
                    if ($publish_data instanceof Carbon) {
                        $q->whereBetween('created_at', [$publish_data->startOfDay(), $publish_data->endOfDay()]);
                    }
                });
            }
            $allCources = $allCources->orderByDesc('id')->get();
            return ResponseHelper::sendResponseSuccess(CourseResource::collection($allCources));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }
    }




    public function store(ProgrammeRequest $request)
    {
        try {
            if(UserPrograme::whereUserId(auth('api')->user()->id)->exists()){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"You Are already Enrolled");
            }
            $enroll = UserPrograme::create([
                "course_id" => $request->course_id,
                "user_id" => auth('api')->user()->id,
                "interview_type" => $request->interview_type
            ]);
            return ResponseHelper::sendResponseSuccess(new ProgrammeResource($enroll));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }


    public function show(string $id)
    {
        try {
            $course =  Courses::with(['majors','user:id,first_name'])->whereId($id)->get();
            if(count($course) < 1){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error on data");
            }
            return ResponseHelper::sendResponseSuccess(CourseResource::collection($course));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function download()
    {
        $certificatequery = UserPrograme::whereUserId(auth('api')->user()->id)->select('certificate')->first();
        if(!$certificatequery){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error on data");
        }
        $certificateName = $certificatequery->certificate;
        return \response()->download(base_path('/public/Institute/certificates/'.$certificateName));
    }

}
