<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\CategoryForm;
use App\Handlers\CategoryHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
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

    public function create(): View
    {
        return CategoryForm::generate(route('admin.categories.store'))->create('创建导航');
    }

    public function store(CategoryRequest $request, Category $category): JsonResponse
    {
        $data = $request->input();
        $category->fill($data);
        try {
            $category->saveOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 422);
        }
        return new JsonResponse(['message' => '创建导航成功', 'redirect' => route('admin.categories.index')]);
    }

    public function edit(Category $category): View
    {
        return CategoryForm::generate(route('admin.categories.update', $category), 'PUT', $category)->create('编辑导航');
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category->fill($request->input());
        try {
            $category->saveOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 422);
        }
        $category->save();
        return new JsonResponse(['message' => '编辑导航成功']);
    }

    public function status(Category $category): JsonResponse
    {
        $category->status = !$category->status;
        $category->save();
        return new JsonResponse(['message' => '更改导航状态成功', 'status' => $category->status]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return new JsonResponse(['message' => '删除导航成功']);
    }
}
