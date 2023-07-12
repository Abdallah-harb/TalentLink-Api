<?php

namespace App\Http\Requests\Api\Public;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {


        return [
            "first_name"=>"required|string|max:200",
            "last_name"=>"required_if:type,job_seeker|nullable|string|max:200",
            "type"=>"required|string|in:job_seeker,company,institute,business_pioneer",
            "mobile"=>"required|string|max:200|unique:users,mobile",
            "email"=>"required|email|max:200|unique:users,email",
            "password"=>"required|string|min:8",
            "password_confirmation"=>"required|string|same:password"
        ];
    }
}
