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
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $user->verified_at !== null;
    }

    public function view(User $user, Order $order): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $user->verified_at !== null && $order->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $user->verified_at !== null;
    }

    public function update(User $user, Order $order): bool
    {
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        return $user->verified_at !== null && $order->user_id === $user->id && $order->status === 'pending';
    }

    // Updated delete method with more granular logic
    public function delete(User $user, Order $order): bool
    {
        // Allow admins to delete any order
        // Staff can delete only pending or processing orders
        return $user->role === 'admin' ||
            ($user->role === 'staff' && in_array($order->status, ['pending', 'processing']));
    }

    public function changeStatus(User $user, Order $order)
    {
        // Only admin/staff can change status
        return $user->role === 'admin' || $user->role === 'staff';
    }
}
