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
            'type_user' => ['required'],
            'name' => ['required_if:type_user,1'],
            'email' => ['required_if:type_user,1', 'unique:users,email'],
            'password' => ['required_if:type_user,1'],
            'rol' => ['required_if:type_user,1', 'required_if:type_user,2'],
            'token_time' => ['required_if:type_user,2']
        ];
    }
}
