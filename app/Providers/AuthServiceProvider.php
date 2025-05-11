<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Tag;
use App\Models\User;
use App\Policies\AddressPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\TagPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
        Order::class => OrderPolicy::class,
        Tag::class => TagPolicy::class,
        Address::class => AddressPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Optional: Define gates for additional permissions
        Gate::define('manage-users', function (User $user) {
            return $user->role === 'admin';
        });

        Gate::define('manage-products', function (User $user) {
            return in_array($user->role, ['admin', 'staff']);
        });
    }
}
