<?php

namespace App\Http\Controllers\Api\Institutes;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\Institute\Courserequest;
use App\Http\Resources\Institutes\CourseResource;
use App\Models\Courses;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allCourses = Courses::whereUserId(auth('api')->user()->id)->get();
        if(count($allCourses) <1){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"No Courses Published Yet ");
        }
        return ResponseHelper::sendResponseSuccess(CourseResource::collection($allCourses));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Courserequest $request)
    {
        DB::beginTransaction();
        try {
            if(Courses::whereUserId(auth('api')->user()->id)->where('course_title',$request->course_title)->exists()){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"This Course You Published Lately");
            }
            $technicalWords = implode(',',$request->input(['technical_words']));
            $personalSkills = implode(',',$request->input(['personal_skills']));
            $course = Courses::create([
                "course_title" => $request->course_title,
                "course_type" => $request->course_type,
                "province_id" =>$request->province_id,
                "city_id" => $request->city_id,
                "course_level" => $request->course_level,
                "start_year" =>$request->start_year,
                "end_year" =>$request->end_year,
                "course_cost" =>$request->course_cost,
                "professor" => $request->professor,
                "duration" => $request->duration,
                "course_description" => $request->course_description,
                "technical_words" =>$technicalWords,
                "personal_skills" =>$personalSkills,
                "user_id" => auth('api')->user()->id
            ]);
            $course->majors()->attach($request->majors);
            DB::commit();
            return ResponseHelper::sendResponseSuccess(new CourseResource($course));
        }catch (\Exception $ex){
            DB::rollBack();
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $course = Courses::whereUserId(auth('api')->user()->id)->whereId($id)->get();
            if(count($course)<1){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error On data");
            }
            return ResponseHelper::sendResponseSuccess(CourseResource::collection($course));

        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Courserequest $request, string $id)
    {
        DB::beginTransaction();
        try {
            $course = Courses::whereUserId(auth('api')->user()->id)->find($id);
            if(!$course){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error On data");
            }
            $technicalWords = implode(',',$request->input(['technical_words']));
            $personalSkills = implode(',',$request->input(['personal_skills']));
            $course->update([
                "course_title" => $request->course_title,
                "course_type" => $request->course_type,
                "province_id" =>$request->province_id,
                "city_id" => $request->city_id,
                "course_level" => $request->course_level,
                "start_year" =>$request->start_year,
                "end_year" =>$request->end_year,
                "course_cost" =>$request->course_cost,
                "professor" => $request->professor,
                "duration" => $request->duration,
                "course_description" => $request->course_description,
                "technical_words" =>$technicalWords,
                "personal_skills" =>$personalSkills
            ]);
            $course->majors()->sync($request->majors);
            DB::commit();
            return ResponseHelper::sendResponseSuccess(new CourseResource($course));
        }catch (\Exception $ex){
            DB::rollBack();
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $course = Courses::whereUserId(auth('api')->user()->id)->find($id);
            if(!$course){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error On data");
            }
            $course->majors()->detach();
            $course->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,"Course Delete Successfully");
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }
}
