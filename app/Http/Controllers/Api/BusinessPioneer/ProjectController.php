<?php

namespace App\Http\Controllers\Api\BusinessPioneer;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\BusinessPioneer\ProjectRequest;
use App\Http\Resources\BusinessPioneer\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::whereUserId(auth('api')->user()->id)->get();
        if(count($projects) <1){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"No project Yet");
        }
        return  ResponseHelper::sendResponseSuccess(ProjectResource::collection($projects));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        try {
            $problem = implode(',',$request->input(['problem']));
            $solving = implode(',',$request->input(['solving']));
            $marked_by = implode(',',$request->input(['marked_by']));

            $project = Project::create([
                "project_title" => $request->project_title,
                "project_type" => $request->project_type,
                "project_nature" => $request->project_nature,
                "problem" => $problem,
                "solving" => $solving,
                "marked_by" => $marked_by,
                "target_group" => $request->target_group,
                "area" => $request->area,
                "need_industry" => $request->need_industry,
                "user_id" => auth('api')->user()->id
            ]);
            return ResponseHelper::sendResponseSuccess(new ProjectResource($project));
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
            $project = Project::whereUserId(auth('api')->user()->id)->whereId($id)->get();
            if(count($project)<1){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error on data");
            }
            return ResponseHelper::sendResponseSuccess(ProjectResource::collection($project));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectRequest $request, string $id)
    {
        try {
            $problem = implode(',',$request->input(['problem']));
            $solving = implode(',',$request->input(['solving']));
            $marked_by = implode(',',$request->input(['marked_by']));
            $project = Project::whereUserId(auth('api')->user()->id)->find($id);
            if(!$project){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,"There are error on data");
            }
            $project->update([
                "project_title" => $request->project_title,
                "project_type" => $request->project_type,
                "project_nature" => $request->project_nature,
                "problem" => $problem,
                "solving" => $solving,
                "marked_by" => $marked_by,
                "target_group" => $request->target_group,
                "area" => $request->area,
                "need_industry" => $request->need_industry
            ]);
            return ResponseHelper::sendResponseSuccess(new ProjectResource($project));
        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
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
