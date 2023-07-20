<?php

namespace App\Providers;

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
        $category = Category::with(['description'])->where('status',1)->get();
        $category_mass=[];
        foreach ($category as $key=> $item) {
            $category_mass[]=$item->toArray();
            $category_mass[$key]['href']=url('/category/'.$category_mass[$key]['category_id']);
        }

        view()->composer('layouts.app', function ($view) use ($category_mass) {
            $view->with('category', $category_mass);
        });
    }
}
