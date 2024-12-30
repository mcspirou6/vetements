<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Order;
use App\Models\WishlistItem;
use App\Policies\AddressPolicy;
use App\Policies\OrderPolicy;
use App\Policies\WishlistItemPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Address::class => AddressPolicy::class,
        Order::class => OrderPolicy::class,
        WishlistItem::class => WishlistItemPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
