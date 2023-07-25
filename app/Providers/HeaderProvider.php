<?php

namespace App\Providers;

use App\Components\HeaderComponent;
use Illuminate\Support\ServiceProvider;

class HeaderProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('Header', function (){
            return new HeaderComponent();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
