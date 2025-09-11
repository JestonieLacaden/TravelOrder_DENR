<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Leave::class => \App\Policies\LeavePolicy::class,
        \App\Models\TravelOrder::class => \App\Policies\TravelOrderPolicy::class,
        \App\Models\SetTravelOrderSignatory::class => \App\Policies\SetTravelOrderSignatoryPolicy::class, // <-- gagawin sa Step 2.3
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}