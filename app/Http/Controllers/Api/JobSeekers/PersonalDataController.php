<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\HelperFile;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\JopSeeker\PersonalDataRequest;
use App\Http\Resources\JopSeeker\PersonalData;
use App\Models\PersonalDataOfJobSeeker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonalDataController extends Controller
{

    public function store(PersonalDataRequest $request)
    {
        try {
            if(PersonalDataOfJobSeeker::where('user_id',auth('api')->user()->id)->exists()){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"this data already exists");
            }
          $cv =   HelperFile::uploadImage($request->the_biography_file,'cv');
            $personalData =  PersonalDataOfJobSeeker::create([
                'full_name' => $request->full_name,
                'title' => $request->title,
                'province_id' => $request->province,
                'city_id' =>$request->city,
                'nationality_id' => $request->nationality,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'marital_status' => $request->marital_status,
                'description' => $request->description,
                "the_biography_file" =>$cv,
                'user_id'=>auth('api')->user()->id
            ]);
            return ResponseHelper::sendResponseSuccess(new PersonalData($personalData));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function show()
    {
        try {
            $personaldata = PersonalDataOfJobSeeker::whereUserId(auth('api')->user()->id)->get();
            if(count($personaldata) < 1){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'No data for yet For this User');
            }
            return  ResponseHelper::sendResponseSuccess(PersonalData::collection($personaldata));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function update(PersonalDataRequest $request)
    {
        try {
            $personalData = PersonalDataOfJobSeeker::whereUserId(auth('api')->user()->id)->first();
            $cv =   HelperFile::uploadImage($request->the_biography_file,'cv');
             $personalData->update([
                'full_name' => $request->full_name,
                'title' => $request->title,
                'province_id' => $request->province,
                'city_id' => $request->city,
                'nationality_id' => $request->nationality,
                'gender' => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'marital_status' => $request->marital_status,
                'description' => $request->description,
                 "the_biography_file" =>$cv
            ]);
            return  ResponseHelper::sendResponseSuccess(new PersonalData($personalData));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

}
