<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', Rule::unique('users')->ignore($this->user->id ?? null)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->user->id ?? null)],
            'photo' => ['nullable', 'regex:/^\/([a-zA-Z0-9]+\/)+[a-zA-Z0-9]+\.(jpg|png|jpeg|gif|webp|svg)$/i'],
            'password' => [Password::min(8)->letters()]
        ];
        if ($this->route()->getName() === 'admin.users.store') {
            $rules['password'][] = 'required';
        } else {
            $rules['password'][] = 'nullable';
        }

        return $rules;
    }
}
