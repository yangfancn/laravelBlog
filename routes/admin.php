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

Route::prefix('manager')->as('admin.')->namespace('\App\Http\Controllers\Admin')->group(function() {
    Route::middleware([
        AuthPermission::class,
    ])->group(function() {
        Route::get('/', [IndexController::class, 'index'])->name('index');
        Route::resource('permissions', PermissionsController::class);
        Route::resource('admins', AdminsController::class);
        Route::resource('roles', RolesController::class);
        Route::resource('posts', PostsController::class);
        Route::resource('categories', CategoriesController::class);
        Route::post('/categories/rank', [CategoriesController::class, 'rank'])->name('categories.rank');
        Route::resource('tags', TagsController::class);
        Route::get('/tags/list/{q}', [TagsController::class, 'list'])->name('tags.list');
        Route::resource('users', UsersController::class);
        Route::get('/sites/edit', [SitesController::class, 'edit'])->name('sites.edit');
        Route::put('/sites/update', [SitesController::class, 'update'])->name('sties.update');
    });

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store'])->name('login');
    Route::delete('/logout', [SessionsController::class, 'destroy'])->name('logout');
});
