<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any products.
     */
    public function viewAny(?User $user = null): bool
    {
        // Allow everyone (including guests) to view all products
        return true;
    }

    /**
     * Determine whether the user can view the product.
     */
    public function view(?User $user, Product $product): bool
    {
        // Allow everyone (including guests) to view a specific product
        return true;
    }

    /**
     * Determine whether the user can create products.
     */
    public function create(User $user): bool
    {
        // Only admin and staff can create products
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can update the product.
     */
    public function update(User $user, Product $product): bool
    {
        // Only admin and staff can update products
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can delete the product.
     */
    public function delete(User $user, Product $product): bool
    {
        // Only admin and staff can delete products
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can restore a soft-deleted product.
     */
    public function restore(User $user, Product $product): bool
    {
        // Only admin and staff can restore products
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can permanently delete the product.
     */
    public function forceDelete(User $user, Product $product): bool
    {
        // Only admin and staff can permanently delete products
        return in_array($user->role, ['admin', 'staff']);
    }
}
