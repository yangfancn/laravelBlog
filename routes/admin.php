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
use App\Http\Controllers\Admin\UploadsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Middleware\Admin\AuthPermission;
use Illuminate\Support\Facades\Route;

Route::prefix('manager')->as('admin.')->namespace('\App\Http\Controllers\Admin')->group(function() {
    Route::middleware([
        AuthPermission::class,
    ])->group(function() {
        Route::get('/', [IndexController::class, 'index'])->name('index');

        Route::resource('permissions', PermissionsController::class);
        Route::put('/permissions/status/{permission}', [PermissionsController::class, 'status'])->name('permissions.status');

        Route::resource('admins', AdminsController::class);
        Route::put('/admins/status/{admin}', [AdminsController::class, 'status'])->name('admins.status');

        Route::resource('roles', RolesController::class);
        Route::put('/roles/status/{role}', [RolesController::class, 'status'])->name('roles.status');

        Route::resource('posts', PostsController::class);
        Route::put('/posts/status/{post}', [PostsController::class, 'status'])->name('posts.status');

        Route::resource('categories', CategoriesController::class);
        Route::post('/categories/rank', [CategoriesController::class, 'rank'])->name('categories.rank');
        Route::put('/categories/status/{category}', [CategoriesController::class, 'status'])->name('categories.status');

        Route::get('/tags/list', [TagsController::class, 'list'])->name('tags.list');
        Route::resource('tags', TagsController::class);

        Route::resource('users', UsersController::class);
        Route::put('/users/status/{user}', [UsersController::class, 'status'])->name('users.status');

        Route::get('/sites/edit', [SitesController::class, 'edit'])->name('sites.edit');
        Route::put('/sites/update', [SitesController::class, 'update'])->name('sties.update');

        Route::post('/upload/image', [UploadsController::class, 'image'])->name('upload.image');
        Route::post('/upload/file', [UploadsController::class, 'file'])->name('upload.file');
    });

    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store'])->name('login');
    Route::delete('/logout', [SessionsController::class, 'destroy'])->name('logout');

    Route::get('/test', function () {
       echo 'hello world';
    })->name('test');
});
