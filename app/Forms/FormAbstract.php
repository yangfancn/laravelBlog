<?php

namespace App\Forms;

use App\Handlers\Form\FormBuilder;
use Illuminate\Database\Eloquent\Model;

abstract class FormAbstract
{
    abstract public static function generate(string $action, string $method = 'POST', ?Model $model = null): FormBuilder;
}
