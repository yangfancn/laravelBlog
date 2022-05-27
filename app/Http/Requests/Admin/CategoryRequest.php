<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
            'name' => 'required',
            'directory' => 'required|alpha_dash',
            'route' => 'nullable|regex:/^[a-zA-Z][a-zA-Z0-9\.]+[a-z-A-Z0-9]$/',
            'pid' => 'integer|required',
            'show' => 'in:0,1',
            'type' => 'in:0,1'
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '名称',
            'directory' => 'PATH',
            'route' => '路由名称',
            'pid' => '付栏目ID',
            'show' => '展示',
            'type' => '栏目类型'
        ];
    }
}
