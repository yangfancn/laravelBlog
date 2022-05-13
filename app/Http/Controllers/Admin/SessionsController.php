<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends BaseController
{
    public function create(): View | RedirectResponse
    {
        if (auth('admin')->check()) {
            return redirect(route('admin.index'));
        }
        return \view('admin.sessions.create');
    }

    public function store(Request $request): JsonResponse
    {
        $result = [
            'message' => 'success',
            'redirectTo' => null
        ];
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required']
        ]);
        if ($status = Auth::guard('admin')->attempt($credentials, (bool)$request->get('remember'))) {
//            $request->session()->regenerate();
            $result['redirectTo'] = route('admin.index');
        } else {
            $result['message'] = '用户名或密码错误';
        }
        return new JsonResponse($result, $status ? 200 : 400);
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (auth('admin')->check()) {
            auth('admin')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect(route('admin.login'));
    }
}
