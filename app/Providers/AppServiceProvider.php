<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;
use App\Http\Livewire\NavigationMenu; // Ensure this matches the namespace and name of your custom class

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

        Blade::if('hasAccess', function (string $value) {
            $user = Auth::user();

            if ($user === null) {
                return false;
            }

            return $user->hasAccess($value);
        });

        JsonResource::withoutWrapping();

        Livewire::component('navigation-menu', NavigationMenu::class);
    }
}