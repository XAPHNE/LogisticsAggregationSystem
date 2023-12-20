<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Fleet;
use App\Models\User;

class FleetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Fleet');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fleet $fleet): bool
    {
        return $user->checkPermissionTo('view Fleet');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Fleet');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fleet $fleet): bool
    {
        return $user->checkPermissionTo('update Fleet');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fleet $fleet): bool
    {
        return $user->checkPermissionTo('delete Fleet');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Fleet $fleet): bool
    {
        return $user->checkPermissionTo('restore Fleet');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Fleet $fleet): bool
    {
        return $user->checkPermissionTo('force-delete Fleet');
    }
}
