<?php
namespace App\Forms\Admin;

use App\Forms\FormAbstract;
use App\Handlers\CategoryHandler;
use App\Handlers\Form\FormBuilder;
use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminForm extends FormAbstract
{
    public static function generate(string $action, string $method = 'POST', null|Admin|Model $model = null):
    FormBuilder
    {
        $checkedRoles = [];
        if ($model) {
            $checkedRoles = $model->roles->pluck('name')->toArray();
            $checkedRoles = array_combine($checkedRoles, $checkedRoles);
        }
        $handler = new CategoryHandler(Permission::get());
        $permissions = $handler->getChannels();
        $roles = Role::pluck('name')->toArray();
        $roles = array_combine($roles, $roles);
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'name', '用户名', $model->name ?? '')
            ->addUploadImage('photo', '头像', $model->photo ?? [], cropper: true, aspectRatio: 1)
            ->addInput('password', 'password', '密码')
            ->addInput('password', 'password_confirmation', '确认密码');
        if (!$model || $model->id !== 1) {
            $form->addCheckbox('roles', '角色', $roles, $checkedRoles);
            foreach ($permissions as $datum) {
                if (isset($datum['children']) && $datum['children']) {
                    $checked = [];
                    $disabled = [];
                    if ($model) {
                        $rolePermissions = $model->getPermissionsViaRoles()->pluck('name')->toArray();
                        foreach ($datum['children'] as $permission) {
                            if ($model->hasPermissionTo($permission['name'])) {
                                $checked[] = $permission['id'];
                                if (in_array($permission['name'], $rolePermissions)) {
                                    $disabled[] = $permission['id'];
                                }
                            }
                        }
                    }
                    $form->addCheckbox('permissions', $datum['description'],
                        Collection::make($datum['children'])->pluck('description', 'id')
                            ->toArray(), $checked, disabled: $disabled);
                } else {
                    $rolePermissions = $model ? $model->getPermissionsViaRoles()->pluck('name')->toArray() : [];
                    $form->addCheckbox('permissions', $datum['description'], [$datum['id'] => $datum['description']],
                        ($model && $model->hasPermissionTo($datum['name'])) ? [$datum['id']] : [], disabled: ($model &&
                            in_array($datum['name'], $rolePermissions)) ? [$datum['id']] : []);
                }
            }
        }

        return $form;
    }
}
