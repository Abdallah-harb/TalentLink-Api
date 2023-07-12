<?php

namespace App\Http\Resources\JopSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AcquireEducationResource extends JsonResource
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
            'language_id'=> $this->language_id,
            'certificate_name'=> $this->certificate_name
        ];
    }
}
