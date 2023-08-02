<?php
namespace App\Providers;
use App\Components\SearchComponent;
use Illuminate\Support\ServiceProvider;
class SearchServiceProvider  extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind('Search', function (){
            return new SearchComponent();
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
