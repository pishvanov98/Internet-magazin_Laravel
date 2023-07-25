<?php

namespace App\Providers;

use App\Components\HeaderComponent;
use App\Models\Category;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    app('Header');//Заполняю хеадер переменными

    }
}
