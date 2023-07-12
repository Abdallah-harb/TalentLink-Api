<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
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
            "ads_name"=>$this->ads_name,
            "description"=>$this->description,
            "ads_img"=>asset("/public/company/ads/".$this->ads_img)

        ];
    }
}
