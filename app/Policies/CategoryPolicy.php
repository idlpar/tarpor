<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Public access
    }

    public function view(User $user, Category $category): bool
    {
        return true; // Public access
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
