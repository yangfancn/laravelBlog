<?php

use App\Http\Controllers\Home\PostsController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\SessionsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/admin.php';

Route::namespace('\App\Http\Controllers\Home')->group(function () {
//    Route::resource('posts', PostsController::class);
});
