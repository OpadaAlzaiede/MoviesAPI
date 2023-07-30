<?php

namespace App\Providers;

use App\Repositories\Category\CategoryDBRepository;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Movie\MovieDBRepository;
use App\Repositories\Movie\MovieRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MovieRepository::class, MovieDBRepository::class);
        $this->app->bind(CategoryRepository::class, CategoryDBRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
