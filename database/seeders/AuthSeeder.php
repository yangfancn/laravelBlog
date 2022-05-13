<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::factory()->create();
        Role::create(['name' => 'super admin', 'guard_name' => 'admin']);
        Admin::first()->assignRole('super admin');

        //default PermissionsController
        $permissions = [
            ['id' => 1, 'description' => '系统用户&权限管理', 'name' => 'admin', 'show' => 1, 'pid' => 0, 'status' => 1, 'rank'
            => 8, 'icon' => 'fa-solid fa-user-lock'],
            ['id' => 2, 'description' => '权限列表', 'name' => 'PermissionsController.index', 'show' => 1, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 3, 'description' => '创建权限', 'name' => 'PermissionsController.create', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 4, 'description' => '保存权限', 'name' => 'PermissionsController.store', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 5, 'description' => '编辑权限', 'name' => 'PermissionsController.edit', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 6, 'description' => '更新权限', 'name' => 'PermissionsController.update', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 7, 'description' => '更改权限状态', 'name' => 'PermissionsController.status', 'show' => 0, 'pid' => 1, 'status'
            => 1, 'rank' => 0],
            ['id' => 8, 'description' => '删除权限', 'name' => 'PermissionsController.destroy', 'show' => 0, 'pid' => 1, 'status'
            => 1, 'rank' => 0],
            ['id' => 9, 'description' => '主界面', 'name' => 'IndexController.index', 'show' => 0, 'pid' => 0, 'status'
            => 1, 'rank' => 0],
            ['id' => 10, 'description' => '图片上传', 'name' => 'UploadsController.image', 'show' => 0, 'pid' => 0, 'status' => 1, 'rank' => 0],
            ['id' => 11, 'description' => '用户列表', 'name' => 'AdminsController.index', 'show' => 1, 'pid' => 1, 'status' => 1, 'rank'
            => 0, 'icon' => 'fa-solid fa-user'],
            ['id' => 12, 'description' => '创建用户', 'name' => 'AdminsController.create', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 13, 'description' => '保存用户', 'name' => 'AdminsController.store', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 14, 'description' => '编辑用户', 'name' => 'AdminsController.edit', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 15, 'description' => '更新用户', 'name' => 'AdminsController.update', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 16, 'description' => '更改用户状态', 'name' => 'AdminsController.status', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 17, 'description' => '删除用户', 'name' => 'AdminsController.destroy', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 18, 'description' => '用户组列表', 'name' => 'RolesController.index', 'show' => 1, 'pid' => 1,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-users'],
            ['id' => 19, 'description' => '创建用户组', 'name' => 'RolesController.create', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 20, 'description' => '保存用户组', 'name' => 'RolesController.store', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 21, 'description' => '编辑用户组', 'name' => 'RolesController.edit', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 22, 'description' => '更新用户组', 'name' => 'RolesController.update', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 23, 'description' => '更改用户组状态', 'name' => 'RolesController.status', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 24, 'description' => '删除用户组', 'name' => 'RolesController.destroy', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 25, 'description' => '文章管理', 'name' => 'post', 'show' => 1, 'pid' => 0,
                'status' => 1, 'rank' => 1, 'icon' => 'fa-solid fa-file-lines'],
            ['id' => 26, 'description' => '文章列表', 'name' => 'PostsController.index', 'show' => 1, 'pid' => 25,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-list-ul'],
            ['id' => 27, 'description' => '创建文章', 'name' => 'PostsController.create', 'show' => 1, 'pid' => 25,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-plus'],
            ['id' => 28, 'description' => '保存文章', 'name' => 'PostsController.store', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 29, 'description' => '编辑文章', 'name' => 'PostsController.edit', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 30, 'description' => '编辑文章', 'name' => 'PostsController.update', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 31, 'description' => '更改文章状态', 'name' => 'PostsController.status', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 32, 'description' => '删除文章', 'name' => 'PostsController.destroy', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 33, 'description' => '导航管理', 'name' => 'CategoriesController.index', 'show' => 1, 'pid' => 0,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-bars'],
            ['id' => 34, 'description' => '创建导航', 'name' => 'CategoriesController.create', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 35, 'description' => '保存导航', 'name' => 'CategoriesController.store', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 36, 'description' => '编辑导航', 'name' => 'CategoriesController.edit', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 37, 'description' => '更新导航', 'name' => 'CategoriesController.update', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 38, 'description' => '更改导航状态', 'name' => 'CategoriesController.status', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 39, 'description' => '更改导航排序', 'name' => 'CategoriesController.rank', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 40, 'description' => '删除导航', 'name' => 'CategoriesController.destroy', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 41, 'description' => '网站设置', 'name' => 'SitesController.edit', 'show' => 1, 'pid' => 0,
                'status' => 1, 'rank' => 2, 'icon' => 'fa-brands fa-internet-explorer'],
            ['id' => 42, 'description' => '更新网站设置', 'name' => 'SitesController.update', 'show' => 0, 'pid' => 41,
                'status' => 1, 'rank' => 0],
            ['id' => 43, 'description' => 'CKEditor图片上传', 'name' => 'UploadsController.ckeditor_upload', 'show' => 0, 'pid' =>
                0, 'status' => 1, 'rank' => 0],
            ['id' => 44, 'description' => '标签列表-ajax', 'name' => 'TagsController.list', 'show' => 0, 'pid' => 25, 'status'
            => 1, 'rank' => 0],
            ['id' => 45, 'description' => '用户管理', 'name' => 'UsersController.index', 'show' => 1, 'pid' => 0, 'status'
            => 1, 'rank' => 0, 'icon' => 'fa-solid fa-users'],
            ['id' => 46, 'description' => '创建用户', 'name' => 'UsersController.create', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 47, 'description' => '保存用户', 'name' => 'UsersController.store', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 48, 'description' => '编辑用户', 'name' => 'UsersController.edit', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 49, 'description' => '更新用户', 'name' => 'UsersController.update', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 50, 'description' => '更新用户状态', 'name' => 'UsersController.status', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 51, 'description' => '删除用户', 'name' => 'UsersController.destroy', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 52, 'description' => '退出登录', 'name' => 'SessionsController.destroy', 'show' => 0, 'pid' => 0, 'status'
            => 1, 'rank' => 0],
            ['id' => 53, 'description' => 'Tag 管理', 'name' => 'TagsController.index', 'show' => 1, 'pid' => 0, 'status'
            => 1, 'rank' => 7, 'icon' => 'fa-solid fa-tags'],
            ['id' => 54, 'description' => '创建 Tag', 'name' => 'TagsController.create', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 55, 'description' => '保存 Tag', 'name' => 'TagsController.store', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 56, 'description' => '编辑 Tag', 'name' => 'TagsController.edit', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 57, 'description' => '更新 Tag', 'name' => 'TagsController.update', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 58, 'description' => '删除 Tag', 'name' => 'TagsController.destroy', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 59, 'description' => '排序 Tag', 'name' => 'TagsController.rank', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
        ];
        $permissions = array_map(function ($item) {
            $item['guard_name'] = 'admin';
            $item['icon'] = $item['icon'] ?? null;
            return $item;
        }, $permissions);
        DB::table('permissions')->insert($permissions);
    }
}
