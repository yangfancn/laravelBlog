<?php

namespace App\Http\Middleware\Admin;

use App\Http\Controllers\Admin\IndexController;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthPermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next): Response|JsonResponse|RedirectResponse
    {
        //session check
        if (!auth('admin')->check()) {
            $redirectTo = route('admin.login');
            if ($request->acceptsHtml()) {
                return redirect($redirectTo);
            } else {
                return new JsonResponse([
                    'message' => __('please login'),
                    'redirectTo' => $redirectTo
                ], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        }
        //permission check
        dd($request->route()->getName());
        $action = class_basename($request->route()->getAction()['controller']);
        $permissionName = str_replace('@', '.', $action);
        if (!auth('admin')->user()->can($permissionName)) {
            if ($request->acceptsHtml()) {
                abort(ResponseAlias::HTTP_FORBIDDEN);
            } else {
                return new JsonResponse([
                    'message' => __('no permission'),
                    'redirectTo' => null
                ], ResponseAlias::HTTP_UNAUTHORIZED);
            }
        }
        //active menu id
        $activePermission = Permission::where(['name' => 'IndexController.index', 'guard_name' => 'admin'])->first();
        if ($activePermission['pid'] === 0) {
            $activeMenuId = $activePermission['id'];
            $activeSubMenuId = null;
        } else {
            $activeSubMenuId = $activePermission['id'];
            $activeMenuId = Permission::where(['id' => $activePermission['pid']])->first()['id'] ?? null;
        }
        View::share(compact('activeMenuId', 'activeSubMenuId'));
        return $next($request);
    }
}
