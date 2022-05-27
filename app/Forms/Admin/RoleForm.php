<?php
namespace App\Forms\Admin;

use App\Forms\FormAbstract;
use App\Handlers\CategoryHandler;
use App\Handlers\Form\FormBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleForm extends FormAbstract
{
    public static function generate(string $action, string $method = 'POST', null|Role|Model $model = null): FormBuilder
    {
        $handler = new CategoryHandler(Permission::get());
        $data = $handler->getChannels();
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'name', '角色名', $model->name ?? '')
            ->addRadio('status', '状态', [1 => '启用', 0 => '禁用'], $model->status ?? 1);
        foreach ($data as $datum) {
            if (isset($datum['children']) && $datum['children']) {
                $checked = [];
                if ($model) {
                    foreach ($datum['children'] as $permission) {
                        if ($model->hasPermissionTo($permission['name'])) {
                            $checked[] = $permission['id'];
                        }
                    }
                }
                $form->addCheckbox('permissions', $datum['description'],
                    Collection::make($datum['children'])->pluck('description', 'id')->toArray(), $checked);
            } else {
                $form->addCheckbox('permissions', $datum['description'], [$datum['id'] => $datum['description']],
                    ($model && $model->hasPermissionTo($datum['name'])) ? [$datum['id']] : []);
            }
        }
        return $form;
    }
}
