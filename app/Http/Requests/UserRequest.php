<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
            'name' => ['required', 'max:55'],
            'phone' => ['nullable', 'min:7', 'numeric'],
            'email' => ['required', 'email',  Rule::unique('users', 'email')->ignore($this->user)]

        ];

        if ($this->isMethod('post')) {
            // Password is required for creating a new user
            $rules['password'] = ['required', 'min:8'];
        } elseif ($this->isMethod('put') || $this->isMethod('patch')) {
            // Password is optional for updating an existing user
            $rules['password'] = ['nullable', Password::min(8)->numbers()->letters()->symbols()];
        }

        return $rules;
    }
}
