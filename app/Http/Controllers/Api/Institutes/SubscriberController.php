<?php

namespace App\Http\Controllers\Api\Institutes;

use App\Events\SendNotifEvent;
use App\Http\Controllers\Controller;
use App\Http\Helper\HelperFile;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\Institute\CertificateRequest;
use App\Http\Resources\JopSeeker\ProgrammeResource;
use App\Models\Notification;
use App\Models\UserPrograme;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
           $allSubscribers = UserPrograme::whereStatus('enrolled')
                             ->with(['course','user.personaldata','user.prev_job','user.prev_experes','user.education','user.aquireLearning'])
                             ->paginate(5);
            if(count($allSubscribers) < 0){
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Subscribers Yet ');
            }
            return ResponseHelper::sendResponseSuccess(ProgrammeResource::collection($allSubscribers));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function show(string $id)
    {
        try {
            $info = UserPrograme::whereId($id)->
            with(['course','user.personaldata','user.prev_job','user.prev_experes','user.education','user.aquireLearning'])->get();
            if(count($info) < 1){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are errors on data');
            }

            return ResponseHelper::sendResponseSuccess(ProgrammeResource::collection($info));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function update(CertificateRequest $request, string $id)
    {
        DB::beginTransaction();
        try {
              $info = UserPrograme::whereId($id)->whereStatus('enrolled')->with(['course','user'])->first();
            if(!$info){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are errors on data');
            }

            $certificate = HelperFile::uploadImage($request->certificate,'certificates');
            $info->update([
                'status' => "completed",
                "certificate" => $certificate
            ]);

            //save to notifications table
            $username =   $info->user->first_name ;
            $courseName =   $info->course->course_title ;
            Notification::create([
                "user_id" =>  $info->user_id,
                "title" => "Graduate from course",
                "message" => "congratulation"." ".$username ." you pass the Qualifying programme",
            ]);

            //send notification to subscribe that pass the course by pusher
            $data = [
                "title" => "Graduate from course",
                "message" => "congratulation" ." ".  $username ." you pass the Qualifying programme",
                "user_id" => $info->user_id,
            ];
            event(new SendNotifEvent($data));
            DB::commit();
            return ResponseHelper::sendResponseSuccess(new ProgrammeResource($info));
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
