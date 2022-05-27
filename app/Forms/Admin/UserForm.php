<?php
namespace App\Forms\Admin;

use App\Forms\FormAbstract;
use App\Handlers\Form\FormBuilder;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserForm extends FormAbstract
{
    public static function generate(string $action, string $method = 'POST', User|Model|null $model = null): FormBuilder
    {
        $form = new FormBuilder($action, $method);
        $form->addInput('text', 'name', 'Name', $model->name ?? '')
            ->addInput('email', 'email', 'E-mail', $model->email ?? '')
            ->addUploadImage('photo', '头像', $model->photo ?? [], cropper: true, aspectRatio: 1)
            ->addInput('password', 'password', '密码');
        return $form;
    }
}
