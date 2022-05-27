<?php
namespace App\Forms\Admin;

use App\Forms\FormAbstract;
use App\Handlers\CategoryHandler;
use App\Handlers\Form\FormBuilder;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;

class CategoryForm extends FormAbstract
{
    public static function generate(string $action, string $method = 'POST', Category|Model|null $model = null):
    FormBuilder
    {
        $handler = new CategoryHandler(Category::orderBy('rank')->get());
        $channels = $handler->buildLevelName($handler->getChannels(0, true));
        $options = [0 => '顶级栏目'];
        foreach ($channels as $channel) {
            if ($model && $model->id === $channel->id) {
                continue;
            }
            $options[$channel->id] = $channel['level_name'];
        }
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'name', '名称', $model->name ?? '')
            ->addInput('text', 'directory', 'PATH', $model->directory ?? '')
            ->addInput('text', 'route', 'Route name', $model->route ?? '')
            ->addSelect('pid', '父栏目', $options, $model->pid ?? 0)
            ->addInput('text', 'seo_title', 'SEO TITLE', $model->seo_title ?? '')
            ->addInput('text', 'seo_keywords', 'SEO KEYWORDS', $model->seo_keywords ?? '')
            ->addTextarea('seo_description', 'SEO DESCRIPTION', $model->seo_description ?? null)
            ->addInput('text', 'direct', '跳转地址', $model->direct ?? '')
            ->addRadio('show', '展示', [0 => '隐藏', 1 => '展示'], $model->show ?? 1)
            ->addRadio('type', '类型', [0 => '单页面', 1 => '文章栏目'], $model->type ?? 0);

        return $form;
    }

}
