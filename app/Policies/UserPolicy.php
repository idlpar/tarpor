<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin' || $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    public function updateAvatar(User $user, User $model): bool
    {
        return $user->id === $model->id && $user->verified_at !== null;
    }
    public function deleteAvatar(User $user, User $model): bool
    {
        \Log::info('deleteAvatar policy check', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'model_id' => $model->id,
            'model_role' => $model->role
        ]);
        return in_array($user->role, ['user', 'staff', 'admin']);

    }

    public function updateAddress(User $user, User $model): bool
    {
        return $user->id === $model->id && $user->verified_at !== null;
    }
}
