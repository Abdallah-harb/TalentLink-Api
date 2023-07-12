<?php

namespace App\Http\Resources\JopSeeker\Job;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobOrderResource extends JsonResource
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
            "major_id" => $this->major_id,
            "province_id"=>$this->province_id,
            "city_id"=>$this->city_id,
            "job_level"=>$this->job_level,
            "start_year"=>$this->start_year,
            "end_year"=>$this->end_year,
            "start_salary"=>$this->start_salary,
            "end_salary" =>$this->end_salary,
            "agreement_with_employee"=> $this->agreement_with_employee,
            "technical_words" =>$this->technical_words,
            "personal_skills"=>$this->personal_skills,
            "user_id" => $this->user_id
        ];
    }
}
