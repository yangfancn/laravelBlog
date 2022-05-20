<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'required|min:3|max:62',
            'category_id' => 'required|integer',
            'user_id' => 'required|integer',
            'tags' => 'array',
            'thumb' => ['regex:/^\/([a-zA-Z0-9]+\/)+[a-zA-Z0-9]+\.(jpg|png|jpeg|gif|webp|svg)$/i'],
            'summary' => 'between:3,120',
            'created_at' => 'date|nullable',
            'content' => 'required'
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => '标题',
            'category_id' => '分类',
            'user_id' => '用户',
            'tags' => '标签Tag',
            'thumb' => '封面',
            'summary' => '简介',
            'created_at' => '创建时间',
            'content' => '正文'
        ];
    }
}
