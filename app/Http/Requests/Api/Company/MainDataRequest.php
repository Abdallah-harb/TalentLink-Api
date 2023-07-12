<?php

namespace App\Http\Requests\Api\Company;

use Illuminate\Foundation\Http\FormRequest;

class MainDataRequest extends FormRequest
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
            "date_establishment" => 'required|date',
            "registration_number" => 'required|numeric',
            "link" => "required|url",
            "logo" => "required|mimes:jpeg,png,jpg,gif|max:2048",
            "major_id" => "required|exists:majors,id",
        ];
    }
}
