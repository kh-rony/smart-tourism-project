<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('super-admin', 'App\Policies\AdminPolicy@superAdmin');
        Gate::define('place-admin', 'App\Policies\AdminPolicy@placeAdmin');
        Gate::define('hotel-admin', 'App\Policies\AdminPolicy@hotelAdmin');
        Gate::define('article-admin', 'App\Policies\AdminPolicy@articleAdmin');
        Gate::define('travel-agent', 'App\Policies\AdminPolicy@travelAgent');
    }
}
