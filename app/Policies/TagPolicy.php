<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tag;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return true; // Public access
    }

    public function view(User $user, Tag $tag): bool
    {
        return true; // Public access
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Tag $tag): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Tag $tag): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
