<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        Log::info('Checking viewAny policy', [
            'user_id' => $user->id,
            'verified_at' => $user->verified_at,
            'role' => $user->role,
        ]);
        // Admins, staff, and verified users can view orders
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $user->verified_at !== null;
    }

    public function view(User $user, Order $order): bool
    {
        // Admins and staff can view any order
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        // Normal users can view their own orders if verified
        return $user->verified_at !== null && $order->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        // Admins, staff, and verified users can create orders
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $user->verified_at !== null;
    }

    public function update(User $user, Order $order): bool
    {
        // Admins and staff can update any order
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        // Normal users can update their own orders if status is pending and they are verified
        return $user->verified_at !== null && $order->user_id === $user->id && $order->status === 'pending';
    }

    public function delete(User $user, Order $order): bool
    {
        // Only admins and staff can delete orders
        return in_array($user->role, ['admin', 'staff']);
    }

    public function changeStatus(User $user, Order $order)
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
