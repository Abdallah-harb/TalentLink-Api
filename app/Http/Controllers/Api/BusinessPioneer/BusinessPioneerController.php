<?php

namespace App\Http\Controllers\Api\BusinessPioneer;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\BusinessPioneer\BusinessPioneerRequest;
use App\Http\Resources\BusinessPioneer\BusinessPioneerResource;
use App\Models\PersonalDataOfBusinessPioneer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class BusinessPioneerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businessPioneer= PersonalDataOfBusinessPioneer::whereUserId(auth('api')->user()->id)->first();
        $teamMembers = PersonalDataOfBusinessPioneer::whereParentId($businessPioneer->id)->get();
        if(count($teamMembers)<1){
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'You Havenot Team Yet');
        }
       return ResponseHelper::sendResponseSuccess(BusinessPioneerResource::collection($teamMembers));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BusinessPioneerRequest $request)
    {

        DB::beginTransaction();
        try {
            $user = auth('api')->user()->id;
            $mainData = PersonalDataOfBusinessPioneer::create([
                'full_name' =>  $request->full_name,
                'title' => $request->title,
                'types_education_id' => $request->types_education_id,
                'major_id' => $request->major_id,
                'province_id' =>$request-> province_id,
                'city_id' => $request->city_id,
                'nationality_id' => $request->nationality_id,
                "user_id" => $user
            ]);

            if($request->has('teamMembers')){
                $allData = [];
                foreach ($request->teamMembers as $teammeber){
                    $teamMembers = PersonalDataOfBusinessPioneer::create([
                        'full_name' =>  $teammeber['full_name'],
                        'title' => $teammeber['title'],
                        'types_education_id' => $teammeber['types_education_id'],
                        'major_id' => $teammeber['major_id'],
                        'province_id' => $teammeber['province_id'],
                        'city_id' => $teammeber['city_id'],
                        'nationality_id' => $teammeber['nationality_id'],
                        "parent_id" => $mainData->id
                    ]);

                    $allData[] =  $teamMembers;
                }
                DB::commit();
                return ResponseHelper::sendResponseSuccess(
                    [
                        'mainData' => new BusinessPioneerResource($mainData),
                        'teamMembers'=>  BusinessPioneerResource::collection($allData)
                    ]);
            }
            DB::commit();
            return ResponseHelper::sendResponseSuccess(new BusinessPioneerResource($mainData));

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            if(!PersonalDataOfBusinessPioneer::find($id)){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on the data');

            }
            $checkPersonal =  PersonalDataOfBusinessPioneer::whereUserId(auth('api')->user()->id)->get();

             $children = PersonalDataOfBusinessPioneer::with('children')->whereParentId($id)->get();
             if(count($children) >0){

                 return ResponseHelper::sendResponseSuccess([
                 'BusinessData' =>  BusinessPioneerResource::collection($checkPersonal),
                 'teamMember' =>  BusinessPioneerResource::collection($children)
                 ]);
             }
             return ResponseHelper::sendResponseSuccess(BusinessPioneerResource::collection($checkPersonal));

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    public function update(BusinessPioneerRequest $request,$id)
    {
        try {
            $personalData = PersonalDataOfBusinessPioneer::whereUserId(auth('api')->user()->id)->whereId($id)->first();
            if(!$personalData){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on the data');
            }
            $personalData->update([
                'full_name' =>  $request->full_name,
                'title' => $request->title,
                'types_education_id' => $request->types_education_id,
                'major_id' => $request->major_id,
                'province_id' =>$request-> province_id,
                'city_id' => $request->city_id,
                'nationality_id' => $request->nationality_id
            ]);

            if($request->has('teamMembers')){
                PersonalDataOfBusinessPioneer::whereParentId($id)->delete();
                $allData = [];
                foreach ($request->teamMembers as $teammeber){
                    $teamMembers = PersonalDataOfBusinessPioneer::create([
                        'full_name' =>  $teammeber['full_name'],
                        'title' => $teammeber['title'],
                        'types_education_id' => $teammeber['types_education_id'],
                        'major_id' => $teammeber['major_id'],
                        'province_id' => $teammeber['province_id'],
                        'city_id' => $teammeber['city_id'],
                        'nationality_id' => $teammeber['nationality_id'],
                        "parent_id" => $personalData->id
                    ]);
                    $allData[] =  $teamMembers;
                }
                DB::commit();
                return ResponseHelper::sendResponseSuccess(
                    [
                        'mainData' => new BusinessPioneerResource($personalData),
                        'teamMembers'=>  BusinessPioneerResource::collection($allData)
                    ]);
            }

            DB::commit();
            return ResponseHelper::sendResponseSuccess(new BusinessPioneerResource($personalData));
        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
                $teammeber = PersonalDataOfBusinessPioneer::find($id);
                if(!$teammeber){
                    return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on data');
                }
                $teammeber->delete();
                return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'you delete one of team member');

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
