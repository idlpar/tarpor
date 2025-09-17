<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Address;
use Illuminate\Auth\Access\HandlesAuthorization;

class AddressPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->is_verified;
    }

    public function view(User $user, Address $address): bool
    {
        return $user->id === $address->user_id && $user->is_verified;
    }

    public function create(User $user): bool
    {
        return $user->is_verified;
    }

    public function update(User $user, Address $address): bool
    {
        return $user->id === $address->user_id && $user->is_verified;
    }

    public function delete(User $user, Address $address): bool
    {
        return $user->id === $address->user_id && $user->is_verified;
    }
}
