<?php

namespace App\Http\Controllers\Admin;

use App\Handlers\CategoryHandler;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends BaseController
{
    public function index(Category $category): View
    {
        $handler = new CategoryHandler($category->get());
        $data = $handler->buildLevelName($handler->getChannels(0, true));
        return view('admin.lists.categories', compact('data'));
    }

    public function create()
    {

    }
}
