<?php

use App\Http\Controllers\Admin\AdminsController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\PermissionsController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\SessionsController;
use App\Http\Controllers\Admin\SitesController;
use App\Http\Controllers\Admin\TagsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Middleware\Admin\AuthPermission;
use App\Http\Middleware\AssignUserPermissions;
use Illuminate\Support\Facades\Route;

Route::prefix('manager')->group(function() {
    Route::middleware([
        AuthPermission::class,
        AssignUserPermissions::class,
    ])->group(function() {
        Route::get('/', [IndexController::class, 'index'])->name('admin.index');
        Route::resource('permissions', PermissionsController::class);
        Route::resource('admins', AdminsController::class);
        Route::resource('roles', RolesController::class);
        Route::resource('posts', PostsController::class);
        Route::resource('categories', CategoriesController::class);
        Route::resource('sites', SitesController::class)->only(['edit', 'update']);
        Route::resource('tags', TagsController::class);
        Route::resource('users', UsersController::class);
        Route::get('test', [IndexController::class, 'index']);
    });

    Route::get('/login', [SessionsController::class, 'create'])->name('admin.login');
    Route::post('/login', [SessionsController::class, 'store'])->name('admin.login');
    Route::delete('/logout', [SessionsController::class, 'destroy'])->name('admin.logout');
});


function resourceNames ($controllerName): array
{
    $actions = ['index', 'create', 'store', 'edit', 'update', 'destroy'];
    return array_map(function ($action) use ($controllerName) {
        return "$controllerName.$action";
    }, $actions);
};
