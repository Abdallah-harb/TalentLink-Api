<?php

namespace App\Http\Resources\JopSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgrammeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "course_id"=>$this->course_id,
            "user_id"=>$this->user_id,
            "interview_type"=>$this->interview_type,
            "status"=>$this->status,
            "certificate" => asset("Institute/certificates/".$this->whenNotNull('certificate')),
            "course" => $this->whenLoaded('course'),
            "userInfo" => $this->whenLoaded('user')
        ];
    }
}
