<?php

namespace App\Http\Resources\Company;

use App\Http\Resources\Public\MajorResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" =>$this->id,
            "job_title" =>$this->job_title,
            "job_type"=> $this->job_type,
            //"majors"=>MajorResource::collection($this->whenLoaded('majors')),
            'majors' => $this->majors->map(function ($major) {
                return [
                    'id' => $major->id,
                    'name' => $major->name
                ];
            }),
            "province_id"=>$this->province_id,
            "city_id"=>$this->city_id,
            "job_level"=>$this->job_level,
            "start_year"=>$this->start_year,
            "end_year"=>$this->end_year,
            "start_salary"=>$this->start_salary,
            "end_salary" =>$this->end_salary,
            "agreement_with_employee"=> $this->agreement_with_employee,
            "start_job_data" =>$this->start_job_data,
            "job_description"=>$this->job_description,
            "job_requirement" =>$this->job_requirement,
            "technical_words" =>$this->technical_words,
            "personal_skills"=>$this->personal_skills,
            "user_id" => $this->user_id
        ];
    }
}
