<?php

namespace App\Http\Resources\JopSeeker;

use App\Models\PreviousJobsForPreviousExperiences;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrevExperiencesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->id,
            "major_id"=>$this->major_id,
            "company_name"=>$this->company_name,
            'user_id' => $this->user_id
        ];
    }
}
