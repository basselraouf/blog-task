<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:users,name',
            'phone' => 'required|string|size:11|unique:users,name',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[!@#$%^&*(),.?":{}|<>]/'
            ]
       ];
    }

    public function messages()
    {
        return[
                'name.required' => 'The name is required.',
                'name.unique' => 'The name already exists.',
                'phone.required' => 'The phone number is required.',
                'phone.unique' => 'The phone number already exists.',
                'password.required' => 'The password is required.',
                'password.min' => 'The password must be at least 8 characters long.',
                'password.regex' => 'The password must contain a lowercase letter, an uppercase letter, a number, and a special character.',
        ];
    }
}
