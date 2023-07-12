<?php

namespace App\Http\Resources\JopSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PasicEducationResource extends JsonResource
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
            'typeEducation' => $this->types_education_id,
            'graduation_year' => $this->graduation_year,
            'college_or_institute_name' => $this->college_or_institute_name,
            'user_id' => $this->user_id
        ];
    }
}
