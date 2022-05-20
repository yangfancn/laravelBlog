<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PermissionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
                'regex:/^[a-zA-Z][a-zA-Z\.]+[a-zA-Z]$/',
                Rule::unique('permissions')->ignore($this->permission->id)
            ],
            'description' => ['required'],
            'pid' => ['required', 'integer'],
            'show' => ['required', 'in:0,1'],
            'status' => ['required', 'in:0,1'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '路由名称',
            'description' => '名称',
            'pid' => '父级',
            'show' => '展示',
            'status' => '状态'
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => '路由名称 格式只能为 "namespace.resource.action" ...'
        ];
    }
}
