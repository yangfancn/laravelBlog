<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminRequest extends FormRequest
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
            'name' => [
                'required',
                Rule::unique('admins')->ignore($this->admin->id ?? null)
            ],
            'password' => [
                'min:6', 'confirmed'
            ],
            'photo' => 'required'
        ];
        if ($this->route()->getName() === 'admin.admins.store') {
            $rules['password'][] = 'required';
        } else {
            $rules['password'][] = 'nullable';
        }
        return $rules;
    }
}
