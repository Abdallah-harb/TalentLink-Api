<?php

namespace App\Http\Resources\Public;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAuthResource extends JsonResource
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
            "first_name"=>$this->first_name,
            "last_name"=>$this->last_name,
            "mobile"=>$this->mobile,
            "email"=>$this->email,
            "type"=>$this->type,
            "email_verified_at"=>$this->email_verified_at,
            "mobile_verified_at"=>$this->mobile_verified_at,
            "token"=>$this->token
        ];
    }
}
