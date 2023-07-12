<?php

namespace App\Http\Requests\Api\JopSeeker;

use Illuminate\Foundation\Http\FormRequest;

class ExperienceRequest extends FormRequest
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
            'major_id' =>'required|exists:majors,id',
            'company_name' =>'required|string|max:200',
            'prev_jobs' => 'required|array',
            'prev_jobs.*.Job_name' =>'required|string|max:200',
            'prev_jobs.*.start_year' =>'required|integer|numeric',
            'prev_jobs.*.end_year' =>'required|integer|numeric',
            'prev_jobs.*.workplace' =>'required'
        ];
    }
}
