<?php

namespace App\Providers;

use App\View\Composers\AdminComposer;
use Illuminate\Support\ServiceProvider;
use View;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['admin/lists/*', 'admin/index/*', 'admin_forms/form'], AdminComposer::class);
    }
}
