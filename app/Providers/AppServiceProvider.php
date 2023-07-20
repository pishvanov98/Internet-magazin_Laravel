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
            $data=$item->toArray();
           var_dump($data['parent_id']);
            if($data['parent_id'] = 0) {

                $category_mass[] = $data;
                $category_mass[$key]['href'] = url('/category/' . $category_mass[$key]['category_id']);
            }

        }

        view()->composer('layouts.app2', function ($view) use ($category_mass) {
            $view->with('categories', $category_mass);
        });
    }
}
