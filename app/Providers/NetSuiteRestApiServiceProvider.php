<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class NetSuiteRestApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('netsuite_rest_api', function () {
            return new \GuzzleHttp\Client([
                'base_uri' => config('app.netsuite_api_base_uri'),
                'auth' => [
                    config('app.netsuite_api_consumer_key'),
                    config('app.netsuite_api_consumer_secret'),
                    config('app.netsuite_api_token_id'),
                    config('app.netsuite_api_token_secret'),
                ],
            ]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
