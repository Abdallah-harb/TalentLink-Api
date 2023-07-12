<?php

namespace App\Http\Resources\Institutes;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            "course_title" =>$this->course_title,
            "course_type"=> $this->course_type,
            "companyName" => $this->whenLoaded('user') ,
            'majors' => $this->majors->map(function ($major) {
                return [
                    'id' => $major->id,
                    'name' => $major->name
                ];
            }),
            "province_id"=>$this->province_id,
            "city_id"=>$this->city_id,
            "course_level"=>$this->course_level,
            "start_year"=>$this->start_year,
            "end_year"=>$this->end_year,
            "course_cost"=>$this->course_cost,
            "professor"=>$this->professor,
            "duration"=>$this->duration,
            "course_description"=>$this->course_description,
            "technical_words" =>$this->technical_words,
            "personal_skills"=>$this->personal_skills,
            "user_id" => $this->user_id
        ];
    }
}
