<?php

namespace App\Http\Controllers\Api\JobSeekers;

use App\Http\Controllers\Controller;
use App\Http\Helper\ResponseHelper;
use App\Http\Requests\Api\JopSeeker\ExperienceRequest;
use App\Http\Resources\JopSeeker\PrevExperiencesResource;
use App\Http\Resources\JopSeeker\PrevJobsResource;
use App\Models\PreviousExperiencesOfJobSeeker;
use App\Models\PreviousJobsForPreviousExperiences;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ExperienceController extends Controller
{

    public function store(ExperienceRequest $request)
    {
        DB::beginTransaction();
        try {
            $prviousExperience = PreviousExperiencesOfJobSeeker::create([
                "major_id" => $request->major_id,
                "company_name" => $request->company_name,
                "user_id" => auth('api')->user()->id,
            ]);

               $all_prev_jobs= [];
               $user_id=auth('api')->user()->id;
                foreach ($request->prev_jobs as $prev_job){
                  $prevjobs =  PreviousJobsForPreviousExperiences::create([
                        "Job_name" => $prev_job['Job_name'],
                        "start_year" => $prev_job['start_year'],
                        "end_year" => $prev_job['end_year'],
                        "workplace" => $prev_job['workplace'],
                       "user_id" =>$user_id
                    ]);
                    $all_prev_jobs[]=  $prevjobs;
                }
           /* return (new PrevExperiencesResource($prviousExperience))
                ->additional([
                    'prev_jobs' => new PrevJobsResource($all_prev_jobs)
                ]);*/
                DB::commit();

                return  ResponseHelper::sendResponseSuccess([
                    'prevExperience' => new PrevExperiencesResource($prviousExperience),
                    'prevJobs'       => PrevJobsResource::collection($all_prev_jobs)
                ]);

        }catch (\Exception $ex){
            DB::rollBack();
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }


    public function show()
    {
        try {

            $prviousExperience = PreviousExperiencesOfJobSeeker::where('user_id',auth('api')->user()->id)->get();
            $prvJobs = PreviousJobsForPreviousExperiences::where('user_id',auth('api')->user()->id)->get();
            if(count($prviousExperience) < 1){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'No data Stored yet');

            }
          /* return  ( PrevExperiencesResource::collection($prviousExperience))
                     ->additional([
                    'prev_jobs' => PrevJobsResource::collection($prvJobs)
                     ]);*/

            return  ResponseHelper::sendResponseSuccess([
              "prevExp" =>  PrevExperiencesResource::collection($prviousExperience),
                'prevJobs' =>PrevJobsResource::collection($prvJobs)
            ]);

        }catch (\Exception $ex){
            return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());

        }

    }


    public function update(ExperienceRequest $request)
    {
        DB::beginTransaction();
        try {
            $prviousExperience = PreviousExperiencesOfJobSeeker::where('user_id',auth('api')->user()->id)->firstOrFail();
            if(!$prviousExperience){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'This User Not Add Experience Yet');
            }
            $prviousExperience->update([
                "major_id" => $request->major_id,
                "company_name" => $request->company_name
            ]);
            $allprevjobs = [];
            $user_id = auth('api')->user()->id;
            PreviousJobsForPreviousExperiences::where('user_id', $user_id)->delete();
            foreach ($request->prev_jobs as $prev_job) {
                $prevJobData = [
                    "Job_name" => $prev_job['Job_name'],
                    "start_year" => $prev_job['start_year'],
                    "end_year" => $prev_job['end_year'],
                    "workplace" => $prev_job['workplace'],
                    "user_id" => $user_id
                ];

                $updateprev = PreviousJobsForPreviousExperiences::create($prevJobData);
                $allprevjobs[] = $updateprev;
              }
                DB::commit();
              return ResponseHelper::sendResponseSuccess([
                  'prevExperience' => new PrevExperiencesResource($prviousExperience),
                  'prevJobs' => PrevJobsResource::collection($allprevjobs),
              ],Response::HTTP_OK,'Data Updated Successfully');
          }catch (\Exception $ex){
            DB::rollBack();
              return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
          }

    }

    public function destroy($id){
        try {
            $prevJop = PreviousJobsForPreviousExperiences::whereUserId(auth('api')->user()->id)->find($id);
            if(!$prevJop){
                return ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,'No Prev Jobs For This id');
            }
            $prevJop->delete();
            return ResponseHelper::sendResponseSuccess([],Response::HTTP_OK,'This Prev Job Deleted Successfully');
        }catch (\Exception $ex){
            return  ResponseHelper::sendResponseError([],Response::HTTP_BAD_REQUEST,$ex->getMessage());
        }
    }

}
