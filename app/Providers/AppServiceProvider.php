<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register repos specific for your system
        $this->registerRepos();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register specific repos for this system
     *
     * @return void
     */
    public function registerRepos() {
        // Put your repos in here
        $this->app->bind(
            'App\Repositories\Interfaces\UserRepoInterface',
            'App\Repositories\UserRepo'
        );
        $this->app->bind(
            'App\Repositories\Interfaces\ContainerRepoInterface',
            'App\Repositories\ContainerRepo'
        );
    }

}
