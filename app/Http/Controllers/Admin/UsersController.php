<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\UserForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UsersController extends Controller
{
    public function index(Request $request, User $user): View
    {
        if ($search = $request->input('search')) {
            $user = $user->where('name', 'like', "%$search%");
        }
        $data = $user->paginate(15);
        return view('admin.lists.users', compact('data', 'search'));
    }

    public function create(): View
    {
        return UserForm::generate(route('admin.users.store'))->create('创建用户');
    }

    public function store(UserRequest $request, User $user): JsonResponse
    {
        $data = $request->input();
        $data['password'] = bcrypt($data['password']);
        $user->email_verified_at = now();
        $user->fill($data);
        try {
            $user->saveOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        return new JsonResponse(['message' => '创建用户成功', 'redirect' => route('admin.users.index')]);
    }

    public function edit(User $user): View
    {
        return UserForm::generate(route('admin.users.update', $user), 'PUT', $user)->create('编辑用户资料');
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $data = $request->input();
        if (array_key_exists('password', $data)) {
            if (!$data['password']) {
                unset($data['password']);
            } else {
                $data['password'] = bcrypt($data['password']);
            }
        }
        $user->fill($data);
        try {
            $user->saveOrFail();
        } catch (\Throwable $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], ResponseAlias::HTTP_UNPROCESSABLE_ENTITY);
        }
        return new JsonResponse(['message' => '更新用户成功']);
    }

    public function status(User $user): JsonResponse
    {
        $user->status = !$user->status;
        $user->save();
        return new JsonResponse(['message' => '更新状态成功', 'status' => $user->status]);
    }

    public function destroy(User $user): JsonResponse
    {
        $user->delete();
        return new JsonResponse(['message' => '删除用户成功']);
    }
}
