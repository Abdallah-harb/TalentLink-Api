<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Resources\Institutes\CourseResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CourseFavController extends Controller
{

    public function index()
    {
        try {
            $favCourses = auth('api')->user()->favCourses()->orderByDesc('id')->get();
            if(count($favCourses) <1){
                return  ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'No Courses On fav Yet');
            }
            return ResponseHelper::sendResponseSuccess(CourseResource::collection($favCourses));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
    public function store()
    {
        if(!auth('api')->user()->favCoursesHas(request('course_id'))){
            auth('api')->user()->favCourses()->attach(request('course_id'));
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"course add to fav");
        }
        return ResponseHelper::sendResponseSuccess([],Response::HTTP_BAD_REQUEST,"course already exists on fav");

    }
    public function destroy()
    {
        try {
            $course_id = \request('course_id');
            auth('api')->user()->favCourses()->detach($course_id);
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Course Delete from Fav");
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_BAD_REQUEST,"course already exists on fav");
        }
    }
}
