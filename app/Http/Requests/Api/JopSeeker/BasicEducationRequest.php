<?php

namespace App\Http\Requests\Api\JopSeeker;

use Illuminate\Foundation\Http\FormRequest;

class BasicEducationRequest extends FormRequest
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
            "typeEducation" => "required|exists:types_education,id",
            "graduation_year" => "required|numeric",
            "college_or_institute_name"=>"required|string|max:200",
            "acquire_data" => "required|array",
            "acquire_data.*.language_id" => "required",
            "acquire_data.*.certificate_name" => "required|string|max:200"
        ];
    }
}
