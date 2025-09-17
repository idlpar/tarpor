<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user = null): bool
    {
        // Accessible to everyone (including guests)
        return true;
    }

    public function view(?User $user, Category $category): bool
    {
        // Public can view only active categories
        if (!$user || !in_array($user->role, ['admin', 'staff'])) {
            return $category->status === 'active';
        }

        // Staff and admin can view all categories
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Category $category): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function updateOrder(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Category $category): bool
    {
        // Only allow deletion if no children exist
        return in_array($user->role, ['admin', 'staff']) && $category->children->isEmpty();
    }
}
