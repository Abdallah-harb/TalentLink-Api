<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainDataResuorce extends JsonResource
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
            "Data" => $this->date_establishment,
            "RegistrationNumber" => $this->registration_number,
            "link" => asset($this->link),
            "logo" => asset("company/".$this->logo),
            'Major_id'=> $this->major_id,
            "user_ID" => $this->user_id
        ];
    }
}
