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
        $selectedTags = [];
        if ($model) {
            $tags = $model->tags->pluck('name')->toArray();
            $selectedTags = array_combine($tags, $tags);
        }
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'title', '标题', $model->title ?? '')
            ->addSelect('category_id', '栏目', $categories, $model->category_id ?? null)
            ->addSelect('user_id', '用户', User::pluck('name', 'id')->toArray(), $model->user_id ?? null)
            ->addSelect('tags', '标签', [], $selectedTags,
                isTags: true, ajax: route('admin.tags.list'))
            ->addUploadImage('thumb', '封面', $model->thumb ?? [])
            ->addInput('text', 'seo_title', 'SEO TITLE', $model->seo_title ?? '')
            ->addInput('text', 'seo_keywords', 'SEO KEYWORDS', $model->seo_keywords ?? '')
            ->addTextarea('seo_description', 'SEO DESCRIPTION', $model->seo_description ?? '')
            ->addDatetimePicker('created_at', '创建时间', $model->created_at ?? null)
            ->addDatetimePicker('recommend_until', '推荐截至时间', $model->recommend_until ?? null)
            ->addRadio('show', '展示', [0 => '否', 1 => '是'], $model->show ?? 1)
            ->addCkeditor('content', '正文', $model->content ?? '');

        return $form;
    }
}
