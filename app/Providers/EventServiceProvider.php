<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Models\Comment\SpeciesComment;
use App\Observers\SpeciesCommentObserver;

use App\Models\Species;
use App\Observers\SpeciesObserver;


use App\Models\Product;
use App\Observers\ProductObserver;

use App\Models\User;
use App\Observers\UserObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            \SocialiteProviders\Instagram\InstagramExtendSocialite::class.'@handle',
            \SocialiteProviders\InstagramBasic\InstagramBasicExtendSocialite::class.'@handle',
            \SocialiteProviders\Etsy\EtsyExtendSocialite::class.'@handle',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        SpeciesComment::observe(SpeciesCommentObserver::class);
        Species::observe(SpeciesObserver::class);
        Product::observe(ProductObserver::class);
        User::observe(UserObserver::class);
    }
}
