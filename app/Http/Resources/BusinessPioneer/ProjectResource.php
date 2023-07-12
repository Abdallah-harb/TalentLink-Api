<?php

namespace App\Http\Resources\BusinessPioneer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            "project_title" => $this->project_title,
            "project_type" => $this->project_type,
            "project_nature" => $this->project_nature,
            "problem" => $this->problem,
            "solving" => $this->solving,
            "marked_by" => $this->marked_by,
            "target_group" => $this->target_group,
            "area" => $this->area,
            "need_industry" => $this->need_industry,
            "user_id" => $this->user_id
        ];
    }
}
