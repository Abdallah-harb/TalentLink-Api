<?php

namespace App\Http\Resources\BusinessPioneer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusinessPioneerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {

        return [
            'id' =>$this->id,
            'full_name' => $this->full_name,
            'title' => $this->title,
            'types_education_id' => $this->types_education_id,
            'major_id' => $this->major_id,
            'province_id' => $this->province_id,
            'city_id' => $this->city_id,
            'nationality_id' => $this->nationality_id,
            "user_id" =>$this->when($this->user_id !== null,function (){
                return $this->user_id;
            }),
            "parent_id " =>$this->when($this->parent_id !== null,function (){
                return $this->parent_id;
            }),
            "teamMembers" =>BusinessPioneerResource::collection($this->whenLoaded('children')),

        ];
    }
}
