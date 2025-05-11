<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Public access
    }

    public function view(User $user, Product $product): bool
    {
        return true; // Public access
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Product $product): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
