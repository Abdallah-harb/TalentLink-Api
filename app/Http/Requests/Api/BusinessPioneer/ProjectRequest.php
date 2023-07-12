<?php

namespace App\Http\Requests\Api\BusinessPioneer;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "project_title" => "required|string|max:200",
            "project_type" => "required|exists:projecttypes,id",
            "project_nature" => "required|in:industrial,agricultural,commercial",
            "problem" => "required|array|max:200",
            "solving" => "required|array|max:200",
            "marked_by" => "required|array|max:200",
            "target_group" => "required|string|max:200",
            "area" => "required|string|max:200",
            "need_industry" => "required|boolean"
        ];
    }
}
