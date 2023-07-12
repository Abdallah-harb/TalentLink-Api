<?php

namespace App\Http\Controllers\Api\Companies;

use App\Http\Controllers\Controller;
use App\Http\Helper\HelperFile;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\Company\MainDataRequest;
use App\Http\Requests\Api\Company\MainDataUpRequest;
use App\Http\Resources\Company\MainDataResuorce;
use App\Models\CompaniesAndInstitutesData;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MainDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function store(MainDataRequest $request)
    {
        try {
            $logo =  HelperFile::uploadImage($request->logo,'company');
            $mainData = CompaniesAndInstitutesData::create([
                "date_establishment" => $request->date_establishment,
                "registration_number" => $request->registration_number,
                "link" => $request->link,
                "logo" => $logo,
                "major_id" => $request->major_id,
                "user_id" => auth('api')->user()->id
            ]);
            return ResponseHelper::sendResponseSuccess(new MainDataResuorce($mainData));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {

          $mainData = CompaniesAndInstitutesData::whereUserId(auth('api')->user()->id)->get();
            if(count($mainData) < 1){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'This Company Not entire Main dat yet');
            }
            return ResponseHelper::sendResponseSuccess(MainDataResuorce::collection($mainData));

        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MainDataUpRequest $request)
    {

        try {

            $mainData = CompaniesAndInstitutesData::whereUserId(auth('api')->user()->id)->first();
            if(!$mainData){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'This Company Not entire Main dat yet');
            }

            if($request->hasFile('logo')){
                $logo = HelperFile::uploadImage($request->logo,'company');
                $mainData->update(['logo' =>  $logo]);
            }
            $mainData->update([
                "date_establishment" => $request->date_establishment,
                "registration_number" => $request->registration_number,
                "link" => $request->link,
                "major_id" => $request->major_id,
            ]);
            return ResponseHelper::sendResponseSuccess(new MainDataResuorce($mainData));
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
