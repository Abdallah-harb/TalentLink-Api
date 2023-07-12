<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\Public\CityResource;
use App\Http\Resources\Public\LanguageResource;
use App\Http\Resources\Public\MajorResource;
use App\Http\Resources\Public\NationalityResource;
use App\Http\Resources\Public\PersonalSkillsResource;
use App\Http\Resources\Public\ProjectTypeResource;
use App\Http\Resources\Public\ProvinceResource;
use App\Http\Resources\Public\TypeEducationResource;
use App\Models\City;
use App\Models\Language;
use App\Models\Major;
use App\Models\Nationality;
use App\Models\PersonalSkill;
use App\Models\PersonalSkillTranslate;
use App\Models\Projecttype;
use App\Models\Province;
use App\Models\TypesEducation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DataController extends Controller
{
    public function provinces(){
        $provinces = Province::active()->get();
        return ResponseHelper::sendResponseSuccess(ProvinceResource::collection($provinces));
    }


    public function cities($province_id){
        $province = Province::active()->whereId($province_id)->first();
        if(!$province){
            return ResponseHelper::sendResponseError(null,Response::HTTP_BAD_REQUEST , __("message.Not found"));
        }

        $cities = City::active()->get();
        return ResponseHelper::sendResponseSuccess(CityResource::collection($cities));
    }

    public function majors(){
         $majors = Major::active()->get();
         return ResponseHelper::sendResponseSuccess(MajorResource::collection($majors));
    }



    public function languages(){
        $languages = Language::active()->get();
        return ResponseHelper::sendResponseSuccess(LanguageResource::collection($languages));
    }

    public function types_education(){
        $types = TypesEducation::active()->get();
        return ResponseHelper::sendResponseSuccess(TypeEducationResource::collection($types));
    }

    public function nationalities(){
        $nationalities = Nationality::active()->get();
        return ResponseHelper::sendResponseSuccess(NationalityResource::collection($nationalities));
    }
    public function projectTypes(){
        $nationalities = Projecttype::active()->get();
        return ResponseHelper::sendResponseSuccess(ProjectTypeResource::collection($nationalities));
    }

}
