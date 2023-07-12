<?php

namespace App\Http\Requests\Api\Institute;

use Illuminate\Foundation\Http\FormRequest;

class Courserequest extends FormRequest
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
            "course_title" => 'required|string|max:100',
            "course_type" => "required|string|in:Practical,theoretical,PracticalAndTheoretical",
            "majors" => "required|array",
            "province_id" => "required|exists:provinces,id",
            "city_id" => "required|exists:cities,id",
            "course_level" => "required|in:advanced,intermediate,freshGraduated,highExperience,manager",
            "start_year" =>"required|numeric",
            "end_year" =>"required|numeric",
            "course_cost" => "required|numeric",
            "professor" => "required|string|max:100",
            "duration" => "required|string|max:100",
            "course_description" => "required|string",
            "technical_words" => "required|array",
            "personal_skills" => "required|array"
        ];
    }
}
