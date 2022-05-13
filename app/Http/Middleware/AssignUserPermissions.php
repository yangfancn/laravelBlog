<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;

class AssignUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $user = auth('admin')->user();
        if ($user->hasRole('super admin')) {
            $permissions = Permission::all();
        } else {
            $permissions = auth('admin')->user()->getAllPermissions();
        }
        $menu = [];
        foreach ($permissions->sortBy('pid')->toArray() as $item) {
            if ($item['pid'] === 0) {
                $menu[$item['id']] = $item;
            } else {
                $menu[$item['pid']]['children'][] = $item;
            }
        }
        array_multisort(array_column($menu, 'rank'), SORT_ASC, $menu);
        foreach ($menu as $key => $channel) {
            if (isset($channel['children']) && $channel['children']) {
                array_multisort(array_column($channel['children'], 'rank'), SORT_ASC, $channel['children']);
                $menu[$key]['children'] = $channel['children'];
            }
        }
        View::share('menu', $menu);
        return $next($request);
    }
}
