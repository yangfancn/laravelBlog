<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\RoleForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends BaseController
{
    public function index(Request $request, Role $role): View
    {
        if ($search = $request->input('search')) {
            $role->where('name', 'like', "%$search%");
        }
        $data = $role->paginate(15);
        return view('admin.lists.roles', compact('search', 'data'));
    }

    public function create(): View
    {
        return RoleForm::generate(route('admin.roles.store'))->create('创建角色');
    }

    public function store(RoleRequest $request, Role $role): JsonResponse
    {
        $data = $request->input();
        $data['guard_name'] = 'admin';
        $role->fill($data);
        try {
            $role->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 422);
        }

        $role->syncPermissions(Permission::whereIn('id', $data['permissions'])->pluck('name')->toArray());
        return new JsonResponse(['message' => '创建角色成功', 'redirect' => route('admin.roles.index')]);
    }

    public function edit(Role $role): View
    {
        return RoleForm::generate(route('admin.roles.update', $role), 'PUT', $role)->create('编辑角色');
    }

    public function update(Request $request, Role $role): JsonResponse
    {
        $data = $request->input();
        $role->guard_name = 'admin';
        $role->fill($data);
        try {
            $role->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 422);
        }
        $role->syncPermissions(Permission::whereIn('id', $data['permissions'])->pluck('name')->toArray());
        return new JsonResponse(['message' => '更新角色成功']);
    }

    public function status(Role $role): JsonResponse
    {
        $role->status = !$role->status;
        $role->save();
        return new JsonResponse(['message' => '更新状态成功', 'status' => $role->status]);
    }

    public function destroy(Role $role): JsonResponse
    {
        $role->delete();
        return new JsonResponse(['message' => '删除角色成功']);
    }
}
