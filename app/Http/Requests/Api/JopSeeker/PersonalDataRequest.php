<?php

namespace App\Http\Requests\Api\JopSeeker;

use Illuminate\Foundation\Http\FormRequest;

class PersonalDataRequest extends FormRequest
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

            'full_name' => 'required|string|max:80',
            'title' => 'required|string|max:50',
            'province' => 'required|exists:majors,id',
            'city' => 'required|exists:cities,id',
            'nationality' => 'required|exists:nationalities,id',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required',
            'marital_status' => 'required|in:single, married, absolute, widower',
            'description' => 'required|string|max:200',
            "the_biography_file" => "nullable|mimes:pdf|max:10000"

        ];
    }
}
