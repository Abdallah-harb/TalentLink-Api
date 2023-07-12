<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\JopSeeker\BasicEducationRequest;
use App\Http\Resources\JopSeeker\AcquireEducationResource;
use App\Http\Resources\JopSeeker\PasicEducationResource;
use App\Models\AcquiredLearningData;
use App\Models\BasicEducationData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class EducationDataController extends Controller
{

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $pasicEducation = BasicEducationData::create([
                "types_education_id" => $request->typeEducation,
                "graduation_year" => $request->graduation_year,
                "college_or_institute_name" => $request->college_or_institute_name,
                "user_id" => auth('api')->user()->id
            ]);


            $allAcquiredata =[];
            $user = auth('api')->user()->id;
            $acquiredatas = $request->acquire_data;
            foreach ($acquiredatas as $acquiredata){
                $languages = is_array($acquiredata['language_id']) ? $acquiredata['language_id'] : [$acquiredata['language_id']];
                foreach ($languages as $langiage){
                    $allAcquire =    AcquiredLearningData::create([
                        'language_id' =>$langiage,
                        'certificate_name' => $acquiredata['certificate_name'],
                        'user_id' =>$user
                    ]);
                    $allAcquiredata []= $allAcquire;
                }
            }

            DB::commit();
            return ResponseHelper::sendResponseSuccess([
                'pasicEducation' => new PasicEducationResource($pasicEducation),
                'acquirEducation' => AcquireEducationResource::collection($allAcquiredata)
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }


    public function show()
    {
        try {
            $pasicEducation = BasicEducationData::whereUserId(auth('api')->user()->id)->get();
            $allAcquire =    AcquiredLearningData::whereUserId(auth('api')->user()->id)->get();
            if(! $pasicEducation && !$allAcquire){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'there arenot data stored yet');
            }
            return ResponseHelper::sendResponseSuccess([
                'basicEducation' => PasicEducationResource::collection($pasicEducation),
                'acquireEducation' => AcquireEducationResource::collection($allAcquire)
            ]);
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }

    }


    public function update(BasicEducationRequest $request)
    {
        DB::beginTransaction();
        try {
            $pasicEducation = BasicEducationData::whereUserId(auth('api')->user()->id)->first();
            if(! $pasicEducation ){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'there arenot data stored yet');
            }

            $pasicEducation->update([
                "types_education_id" => $request->typeEducation,
                "graduation_year" => $request->graduation_year,
                "college_or_institute_name" => $request->college_or_institute_name
            ]);



            $allAcquiredata =[];
            $user = auth('api')->user()->id;
            AcquiredLearningData::whereUserId($user)->delete();
            $acquiredatas = $request->acquire_data;
            foreach ($acquiredatas as $acquiredata){
                $languages = is_array($acquiredata['language_id']) ? $acquiredata['language_id'] : [$acquiredata['language_id']];
                foreach ($languages as $langiage){
                    $allAcquire =    AcquiredLearningData::create([
                        'language_id' =>$langiage,
                        'certificate_name' => $acquiredata['certificate_name'],
                        'user_id' =>$user
                    ]);
                    $allAcquiredata []= $allAcquire;
                }
            }
            DB::commit();
            return ResponseHelper::sendResponseSuccess([
                'basicEducation' =>new PasicEducationResource($pasicEducation),
                'acquireEducation' => AcquireEducationResource::collection($allAcquiredata)
            ]);
        }catch (\Exception $ex){
            DB::rollBack();
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function destroy($id){
        try {
            $aquireData = AcquiredLearningData::whereUserId(auth('api')->user()->id)->find($id);
            if(!$aquireData){

                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'No Data For This Id');
            }
            $aquireData->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'Data Delete successfully');
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }

    }

}
