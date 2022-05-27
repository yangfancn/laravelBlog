<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\AdminForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminRequest;
use App\Models\Admin;
use http\Exception\RuntimeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminsController extends BaseController
{
    public function index(Request $request, Admin $admin): View
    {
        $where = [];
        if ($search = $request->input('search')) {
            $where[] = ['name', 'like', "%$search%"];
        }
        $data = $admin->with(['roles'])->where($where)->paginate(15);
        return view('admin.lists.admins', compact('data', 'search'));
    }

    public function create(): View
    {
        return AdminForm::generate(route('admin.admins.store'))->create('创建管理员');
    }

    public function store(AdminRequest $request, Admin $admin): JsonResponse
    {
        $data = $request->input();
        $data['password'] = bcrypt($data['password']);
        try {
            $this->saveAdmin($admin, $data);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['message' => '创建管理员成功', 'redirect' => route('admin.admins.index')]);
    }

    public function edit(Admin $admin): View
    {
        return AdminForm::generate(route('admin.admins.update', $admin), 'PUT', $admin)->create('编辑管理员');
    }

    public function update(AdminRequest $request, Admin $admin): JsonResponse
    {
        $data = $request->input();
        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        try {
            $this->saveAdmin($admin, $data);
        } catch (\RuntimeException $e) {
            return new JsonResponse(['message' => $e->getMessage()], $e->getCode());
        }
        return new JsonResponse(['message' => '更新管理员成功']);
    }

    protected function saveAdmin(Admin $admin, array $data): Admin
    {
        $admin->fill($data);
        try {
            $admin->saveOrFail();
        } catch (\Throwable $e) {
            throw new RuntimeException($e->getMessage(), 422);
        }
        $admin->syncRoles($data['roles'] ?? []);
        //get permissions not in user rules
        $rolesPermissions = $admin->getPermissionsViaRoles()->pluck('id')->toArray();
        $admin->syncPermissions(array_diff($data['permissions'] ?? [], $rolesPermissions));
        return $admin;
    }

    public function status(Admin $admin): JsonResponse
    {
        if ($admin->id === 1) {
            return new JsonResponse(['message' => '默认超级管理员不可更改状态'], 422);
        }
        $admin->status = !$admin->status;
        $admin->save();
        return new JsonResponse(['message' => '状态更新成功', 'status' => $admin->status]);
    }

    public function destroy(Admin $admin): JsonResponse
    {
        if ($admin->id === 1) {
            return new JsonResponse(['message' => '默认超级管理员不可删除'], 422);
        }
        $admin->delete();
        return new JsonResponse(['message' => '删除管理员成功']);
    }
}
