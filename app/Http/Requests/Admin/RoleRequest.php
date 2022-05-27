<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoleRequest extends FormRequest
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
        return [
            'name' => [
                'required',
                Rule::unique('roles')->where(function ($query) {
                    return $query->where('name', $this->request->get('name'))->where('guard_name', 'admin');
                })->ignore($this->role->id ?? null)
            ],
            'status' => 'required|in:0,1',
            'permissions' => 'array'
        ];
    }
}
