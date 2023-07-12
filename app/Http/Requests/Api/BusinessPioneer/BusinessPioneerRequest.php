<?php

namespace App\Http\Requests\Api\BusinessPioneer;

use Illuminate\Foundation\Http\FormRequest;

class BusinessPioneerRequest extends FormRequest
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
           'full_name' => "required|max:100|string",
           'title' => "required|max:100|string",
           'types_education_id' => "required|exists:types_education,id",
           'major_id' => "required|exists:majors,id",
           'province_id' => "required|exists:provinces,id",
           'city_id' => "required|exists:cities,id",
           'nationality_id' => "required|exists:nationalities,id"
        ];
    }
}
