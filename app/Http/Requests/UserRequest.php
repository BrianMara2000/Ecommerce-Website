<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'phone' => ['nullable', 'min:7', 'numeric'],
        ];

        if ($this->isMethod('post')) {
            // Password is required for creating a new user
            $rules['name'] = ['required', 'max:55'];
            $rules['email'] = ['required', 'email', 'unique:users,email'];
            $rules['password'] = ['required', 'min:8'];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Password is optional for updating an existing user
            $rules['name'] = ['nullable', 'max:55'];
            $rules['email'] = ['nullable', 'email'];
            $rules['password'] = ['nullable', Password::min(8)->numbers()->letters()->symbols()];
        }

        return $rules;
    }
}
