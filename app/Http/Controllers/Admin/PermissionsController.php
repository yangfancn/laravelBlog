<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\PermissionForm;
use App\Handlers\CategoryHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermissionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class PermissionsController extends BaseController
{
    public function index(Permission $permission): View
    {
        $handler = new CategoryHandler($permission->get());
        $data = $handler->buildLevelName($handler->getChannels(list: true), field: 'description');
        return view('admin.lists.permissions', compact('data'));
    }

    public function create(): View
    {
        return PermissionForm::generate(route('admin.permissions.store'))->create('创建权限');
    }

    public function store(PermissionRequest $request, Permission $permission): JsonResponse
    {
        $data = $request->validated();
        $permission->fill($data);
        try {
            $permission->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 422);
        }
        return new JsonResponse(['message' => '创建权限成功']);
    }

    public function edit(Permission $permission): View
    {
        return PermissionForm::generate(route('admin.permissions.update', $permission), 'PUT', $permission)->create('编辑权限');
    }

    public function update(PermissionRequest $request, Permission $permission): JsonResponse
    {
        $data = $request->input();
        $permission->fill($data);
        try {
            $permission->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 422);
        }
        return new JsonResponse(['message' => '更新权限成功']);
    }

    public function status(Permission $permission): JsonResponse
    {
        $permission->status = !$permission->status;
        $permission->save();
        return new JsonResponse(['message' => '修改状态成功', 'status' => $permission->status]);
    }

    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();
        return new JsonResponse(['message' => '删除权限成功']);
    }
}
