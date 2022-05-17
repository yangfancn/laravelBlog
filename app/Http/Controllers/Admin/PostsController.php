<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\Form\FormBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {

    }

    public function create(): View
    {
        $form = new FormBuilder(route('admin.posts.store'), 'POST');
        return $form->addInput('text', 'title', '标题', '')
            ->addSelect('category_id', '分类', [
                1 => '语文',
                2 => '数学'
            ], 1, '请选择分类')
            ->addRadio('show', '展示', [
                1 => '是',
                0 => '否'
            ])
            ->addCheckbox('grade', 'test', [
                0 => '苹果',
                1 => '香蕉',
                2 => '西瓜',
                3 => '梨子'
            ])
            ->addDatetimePicker('time', '时间', '', 'Y-m-d H:i', true)
            ->addIconSelect('test', '选择图标')
            ->addRegion('address', '地址', 5)
            ->addCkeditor('content', '正文')
            ->create();
    }
}
