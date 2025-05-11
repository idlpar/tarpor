<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->verified_at !== null || in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id || in_array($user->role, ['admin', 'staff']);
    }

    public function create(User $user): bool
    {
        return $user->verified_at !== null;
    }

    public function update(User $user, Order $order): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}
