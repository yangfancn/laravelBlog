<?php

namespace App\Forms;

use App\Handlers\Form\FormBuilder;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class FormAbstract
{
    abstract public static function generate(string $action, string $method = 'POST', ?Model $model = null): FormBuilder;

}
