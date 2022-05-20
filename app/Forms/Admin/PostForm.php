<?php
namespace App\Forms\Admin;

use App\Forms\FormAbstract;
use App\Handlers\CategoryHandler;
use App\Handlers\Form\Enums\JsonType;
use App\Handlers\Form\FormBuilder;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PostForm extends FormAbstract
{
    public static function generate(string $action, string $method = 'POST', null|Model|Post $model = null): FormBuilder
    {
        $categoryHandler = new CategoryHandler(Category::where('type', 1)->get());
        $categories = $categoryHandler->buildLevelName($categoryHandler->getChannels(0, true), 4);
        $categories = array_column($categories, 'level_name', 'id');
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'title', '标题', $post->title ?? '')
            ->addSelect('category_id', '栏目', $categories, $post->category_id ?? null)
            ->addSelect('user_id', '用户', User::pluck('name', 'id')->toArray(), $post->user_id ?? null)
            ->addSelect('tags', '标签', [], $post->tags ?? null, isTags: true, ajax: route('admin.tags.list'))
            ->addUploadImage('thumb', '封面', $post->thumb ?? [])
            ->addInput('text', 'seo_title', 'SEO TITLE', $post->seo_title ?? '')
            ->addInput('text', 'seo_keywords', 'SEO KEYWORDS', $post->seo_keywords ?? '')
            ->addTextarea('seo_description', 'SEO DESCRIPTION', $post->seo_description ?? '')
            ->addDatetimePicker('created_at', '创建时间', $post->created_at ?? null)
            ->addDatetimePicker('recommend_until', '推荐截至时间', $post->recommend_until ?? null)
            ->addRadio('show', '展示', [0 => '否', 1 => '是'], $post->show ?? 1)
            ->addCkeditor('content', '正文', $post->content ?? '');

        return $form;
    }
}
