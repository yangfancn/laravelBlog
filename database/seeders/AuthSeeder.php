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

        //default permissions
        $permissions = [
            ['id' => 1, 'description' => '系统用户&权限管理', 'name' => 'admin.admin', 'show' => 1, 'pid' => 0, 'status' => 1,
                'rank' => 8, 'icon' => 'fa-solid fa-user-lock'],
            ['id' => 2, 'description' => '权限列表', 'name' => 'admin.permissions.index', 'show' => 1, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 3, 'description' => '创建权限', 'name' => 'admin.permissions.create', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 4, 'description' => '保存权限', 'name' => 'admin.permissions.store', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 5, 'description' => '编辑权限', 'name' => 'admin.permissions.edit', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 6, 'description' => '更新权限', 'name' => 'admin.permissions.update', 'show' => 0, 'pid' => 1, 'status' => 1, 'rank' => 0],
            ['id' => 7, 'description' => '更改权限状态', 'name' => 'admin.permissions.status', 'show' => 0, 'pid' => 1, 'status'
            => 1, 'rank' => 0],
            ['id' => 8, 'description' => '删除权限', 'name' => 'admin.permissions.destroy', 'show' => 0, 'pid' => 1, 'status'
            => 1, 'rank' => 0],
            ['id' => 9, 'description' => '主界面', 'name' => 'admin.index', 'show' => 0, 'pid' => 0, 'status'
            => 1, 'rank' => 0],
            ['id' => 10, 'description' => '上传', 'name' => 'admin.upload', 'show' => 0, 'pid' => 0, 'status'
            => 1, 'rank' => 0],
            ['id' => 11, 'description' => '用户列表', 'name' => 'admin.admins.index', 'show' => 1, 'pid' => 1, 'status' => 1, 'rank'
            => 0, 'icon' => 'fa-solid fa-user'],
            ['id' => 12, 'description' => '创建用户', 'name' => 'admin.admins.create', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 13, 'description' => '保存用户', 'name' => 'admin.admins.store', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 14, 'description' => '编辑用户', 'name' => 'admin.admins.edit', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 15, 'description' => '更新用户', 'name' => 'admin.admins.update', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 16, 'description' => '更改用户状态', 'name' => 'admin.admins.status', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 17, 'description' => '删除用户', 'name' => 'admin.admins.destroy', 'show' => 0, 'pid' => 1, 'status' => 1,
                'rank' => 0],
            ['id' => 18, 'description' => '角色列表', 'name' => 'admin.roles.index', 'show' => 1, 'pid' => 1,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-users'],
            ['id' => 19, 'description' => '创建角色', 'name' => 'admin.roles.create', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 20, 'description' => '保存角色', 'name' => 'admin.roles.store', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 21, 'description' => '编辑角色', 'name' => 'admin.roles.edit', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 22, 'description' => '更新角色', 'name' => 'admin.roles.update', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 23, 'description' => '更改角色状态', 'name' => 'admin.roles.status', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 24, 'description' => '删除角色', 'name' => 'admin.roles.destroy', 'show' => 0, 'pid' => 1,
                'status' => 1, 'rank' => 0],
            ['id' => 25, 'description' => '文章管理', 'name' => 'admin.post', 'show' => 1, 'pid' => 0,
                'status' => 1, 'rank' => 1, 'icon' => 'fa-solid fa-file-lines'],
            ['id' => 26, 'description' => '文章列表', 'name' => 'admin.posts.index', 'show' => 1, 'pid' => 25,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-list-ul'],
            ['id' => 27, 'description' => '创建文章', 'name' => 'admin.posts.create', 'show' => 1, 'pid' => 25,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-plus'],
            ['id' => 28, 'description' => '保存文章', 'name' => 'admin.posts.store', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 29, 'description' => '编辑文章', 'name' => 'admin.posts.edit', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 30, 'description' => '编辑文章', 'name' => 'admin.posts.update', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 31, 'description' => '更改文章状态', 'name' => 'admin.posts.status', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 32, 'description' => '删除文章', 'name' => 'admin.posts.destroy', 'show' => 0, 'pid' => 25,
                'status' => 1, 'rank' => 0],
            ['id' => 33, 'description' => '导航管理', 'name' => 'admin.categories.index', 'show' => 1, 'pid' => 0,
                'status' => 1, 'rank' => 0, 'icon' => 'fa-solid fa-bars'],
            ['id' => 34, 'description' => '创建导航', 'name' => 'admin.categories.create', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 35, 'description' => '保存导航', 'name' => 'admin.categories.store', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 36, 'description' => '编辑导航', 'name' => 'admin.categories.edit', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 37, 'description' => '更新导航', 'name' => 'admin.categories.update', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 38, 'description' => '更改导航状态', 'name' => 'admin.categories.status', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 39, 'description' => '更改导航排序', 'name' => 'admin.categories.rank', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 40, 'description' => '删除导航', 'name' => 'admin.categories.destroy', 'show' => 0, 'pid' => 33,
                'status' => 1, 'rank' => 0],
            ['id' => 41, 'description' => '网站设置', 'name' => 'admin.sites.edit', 'show' => 1, 'pid' => 0,
                'status' => 1, 'rank' => 2, 'icon' => 'fa-brands fa-internet-explorer'],
            ['id' => 42, 'description' => '更新网站设置', 'name' => 'admin.sites.update', 'show' => 0, 'pid' => 41,
                'status' => 1, 'rank' => 0],
            ['id' => 43, 'description' => 'CKEditor图片上传', 'name' => 'admin.upload.ckeditor', 'show' => 0, 'pid' =>
                10, 'status' => 1, 'rank' => 0],
            ['id' => 44, 'description' => '标签列表-ajax', 'name' => 'admin.tags.list', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 45, 'description' => '用户管理', 'name' => 'admin.users.index', 'show' => 1, 'pid' => 0, 'status'
            => 1, 'rank' => 0, 'icon' => 'fa-solid fa-users'],
            ['id' => 46, 'description' => '创建用户', 'name' => 'admin.users.create', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 47, 'description' => '保存用户', 'name' => 'admin.users.store', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 48, 'description' => '编辑用户', 'name' => 'admin.users.edit', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 49, 'description' => '更新用户', 'name' => 'admin.users.update', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 50, 'description' => '更新用户状态', 'name' => 'admin.users.status', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 51, 'description' => '删除用户', 'name' => 'admin.users.destroy', 'show' => 0, 'pid' => 45, 'status'
            => 1, 'rank' => 0],
            ['id' => 52, 'description' => '退出登录', 'name' => 'admin.logout', 'show' => 0, 'pid' => 0, 'status'
            => 1, 'rank' => 0],
            ['id' => 53, 'description' => 'Tag 管理', 'name' => 'admin.tags.index', 'show' => 1, 'pid' => 0, 'status'
            => 1, 'rank' => 7, 'icon' => 'fa-solid fa-tags'],
            ['id' => 54, 'description' => '创建 Tag', 'name' => 'admin.tags.create', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 55, 'description' => '保存 Tag', 'name' => 'admin.tags.store', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 56, 'description' => '编辑 Tag', 'name' => 'admin.tags.edit', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 57, 'description' => '更新 Tag', 'name' => 'admin.tags.update', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 58, 'description' => '删除 Tag', 'name' => 'admin.tags.destroy', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 59, 'description' => '排序 Tag', 'name' => 'admin.tags.rank', 'show' => 0, 'pid' => 53, 'status'
            => 1, 'rank' => 0],
            ['id' => 60, 'description' => '图片上传', 'name' => 'admin.upload.image', 'show' => 0, 'pid' => 10, 'status'
            => 1, 'rank' => 0],
            ['id' => 61, 'description' => '文件上传', 'name' => 'admin.upload.file', 'show' => 0, 'pid' => 10, 'status'
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
