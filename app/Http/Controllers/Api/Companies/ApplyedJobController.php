<?php

namespace App\Http\Controllers\Api\Companies;

use App\Events\SendNotifEvent;
use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\Company\JobApplayResource;
use App\Models\Job;
use App\Models\JobOrder;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ApplyedJobController extends Controller
{

    public function index()
    {
        $allapplayed = Job::whereUserId(auth('api')->user()->id)->with(['userjob'=>['user']])->get();
        return ResponseHelper::sendResponseSuccess(JobApplayResource::collection($allapplayed));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $recommend =UserJob::whereJobId($request->job_id)->first();
            if(!$recommend){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"there are error on data");
            }
            $recommend->update(['recommendation' => 1]);
            $job = Job::whereId($request->job_id)->first();
            $jobName = $job->job_title;
            Notification::create([
                'user_id' => $request->user_id,
                'title' => "Recommendation Job ",
                "message" => "Congratulation You are Recommend For Job as ".$jobName."  And  Company Will Contact With You"
            ]);
            $data = [
                "title" => "Recommendation Job ",
                "message" => "Congratulation You are Recommend For Job as ".$jobName."  And  Company Will Contact With You",
                'user_id' => $request->user_id
            ];
            event(new SendNotifEvent($data));
            DB::commit();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Notification send to Recommendation for the job and will contact with him");
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    //job_seeker recommend for you
    public function jobSeekerRecommend(){
        try {

               return $all = JobOrder::with(['user' => function($query) {
                   $query->select('id', 'first_name', 'last_name')->with(['personaldata']);
               }])->get();
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    //search to get jobSeekers
    public function search(Request $request){
        try {
              return  $ajobSeekers = JobOrder::get();
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

}
