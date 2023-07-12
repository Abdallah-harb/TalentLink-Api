<?php

namespace App\Http\Requests\Api\JopSeeker\Jobs;

use Illuminate\Foundation\Http\FormRequest;

class OrderJobRequest extends FormRequest
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
            "job_title" => 'required|string|max:100',
            "job_type" => "required|string|in:fullTime,partTime,remotely",
            "major_id" => "required|exists:majors,id",
            "province_id" => "required|exists:provinces,id",
            "city_id" => "required|exists:cities,id",
            "job_level" => "required|in:advanced,intermediate,freshGraduated,highExperience,manager",
            "start_year" =>"required|numeric",
            "end_year" =>"required|numeric",
            "start_salary" => "nullable|numeric",
            "end_salary" => "nullable|numeric",
            "agreement_with_employee" =>"nullable|boolean",
            "technical_words" => "required|array",
            "personal_skills" => "required|array"
        ];
    }
}
