<?php
namespace App\View\Composers;

use App\Models\Site;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class AdminComposer
{
    protected array $permissions;
    protected Site $site;
    public function __construct()
    {
        $this->site = Site::first();
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
        $this->permissions = $menu;
    }

    public function compose(View $view): void
    {
        $view->with('site', $this->site);
        $view->with('menu', $this->permissions);
    }
}
