<?php
namespace App\Forms\Admin;

use App\Forms\FormAbstract;
use App\Handlers\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;

class PermissionForm extends FormAbstract
{
    public static function generate(string $action, string $method = 'POST', Model|Permission|null $model = null):
    FormBuilder
    {
        $parents = Permission::where('pid', 0)->pluck('description', 'id')->toArray();
        array_unshift($parents, '顶级栏目');
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'name', '路由名称', $model->name ?? '', 'admin.posts.create')
            ->addInput('text', 'description', '名称', $model->description ?? '')
            ->addSelect('pid', '父级', $parents, $model->pid ?? 0)
            ->addIconSelect('icon', '图标', $model->icon ?? null)
            ->addRadio('show', '左侧菜单展示', [1 => '展示', 0 => '不展示'], $model->show ?? 0)
            ->addRadio('status', '状态', [1 => '启用', 0 => '禁用'], $model->status ?? 1);
        return $form;
    }
}
