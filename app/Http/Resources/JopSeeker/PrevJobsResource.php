<?php

namespace App\Http\Resources\JopSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrevJobsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'Job_name' => $this->Job_name,
            'start_year' => $this->start_year,
            'end_year' => $this->end_year,
            'workplace' => $this->workplace,
            'user_id' => $this->user_id,
        ];
    }
}
