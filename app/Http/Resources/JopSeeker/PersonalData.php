<?php

namespace App\Http\Resources\JopSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonalData extends JsonResource
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
            "full_name"=>$this->full_name,
            "title"=>$this->title,
            "province_id"=>$this->province_id,
            'city_id'  =>$this->city_id,
            "nationality_id"=>$this->nationality_id,
            "gender"=>$this->gender,
            "date_of_birth"=>$this->date_of_birth,
            "description"=>$this->description,
            "the_biography_file" => asset(base_path()."/public/cv/".$this->the_biography_file)
        ];
    }
}
