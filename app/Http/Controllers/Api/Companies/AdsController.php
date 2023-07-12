<?php

namespace App\Http\Controllers\Api\Companies;

use App\Http\Controllers\Controller;
use App\Http\Helper\HelperFile;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\Company\AdsRequest;
use App\Http\Resources\Company\AdsResource;
use App\Models\Advertise;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $ads = Advertise::whereUserId(auth('api')->user()->id)->get();
            if (count($ads)< 1){
                return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No ads Added Yet');
            }
            return ResponseHelper::sendResponseSuccess(AdsResource::collection($ads));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AdsRequest $request)
    {
        try {
            $adsImg = HelperFile::uploadImage($request->ads_img,'ads');
            $ads = Advertise::create([
                'ads_name' =>$request->ads_name,
                "description" =>$request->description,
                "ads_img" =>$adsImg,
                "user_id" => auth('api')->user()->id
            ]);
            return ResponseHelper::sendResponseSuccess(new AdsResource($ads));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $ads = Advertise::whereUserId(auth('api')->user()->id)->whereId($id)->get();
            if (count($ads)< 1){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on data');
            }
            return ResponseHelper::sendResponseSuccess(AdsResource::collection($ads));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AdsRequest $request)
    {
        try {
            $ads = Advertise::whereUserId(auth('api')->user()->id)->whereId($request->id)->first();
            if (!$ads){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on data');
            }
            if(!empty($request->hasFile('ads_img'))){
                $adsImg = HelperFile::uploadImage($request->ads_img,'ads');
                $ads->update(["ads_img" =>$adsImg]);
            }
            $ads->update([
                'ads_name' =>$request->ads_name,
                "description" =>$request->description
            ]);
            return ResponseHelper::sendResponseSuccess(new AdsResource($ads));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $ads = Advertise::whereUserId(auth('api')->user()->id)->whereId($id)->first();
            if (!$ads){
                return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'There are error on data');
            }
            $ads->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'Ads Delete Successfully');
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }
    }
}
