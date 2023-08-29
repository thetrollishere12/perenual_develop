<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::namespace($this->namespace)
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('api-request', function (Request $request) {

            $check = api_key_check($request->key);

            if (!$check) {
                return response()->json(['message' => 'Missing/Issue with API Key. Please try again or contact '.env("MAIL_CONTACT_ADDRESS")],404);
            }

            $subscription = is_subscribed_type($check->user_id,'subscription');

            $details = subscription_details((($subscription->count() > 0) ? $subscription->first()->name : null),'subscription');

            return $subscription->count() > 0
            ? Limit::perMinute($details['bandwidth']['limit'])->by($details['name']."-".$check->user_id)->response(function(Request $request, array $headers){
                $headers['X-Response'] = "Surpassed API Rate Limit";
                return response($headers, 429);
            })
            : Limit::perDay($details['bandwidth']['limit'])->by($details['name']."-".$check->user_id)->response(function(Request $request, array $headers){
                $headers['X-Response'] = "Surpassed API Rate Limit. Upgrade To Increase Rate";
                return response($headers, 429);
            });

        });



        RateLimiter::for('api-request-identify', function (Request $request) {

            $check = api_key_check($request->key);

            if (!$check) {
                return response()->json(['message' => 'Missing/Issue with API Key. Please try again or contact '.env("MAIL_CONTACT_ADDRESS")],404);
            }

        });


    }
}
