<?php

namespace App\Providers;

use App\Models\ProductComment;
use Illuminate\Support\ServiceProvider;
use App\Observers\ProductCommentObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ProductComment::observe(ProductCommentObserver::class);
    }
}
