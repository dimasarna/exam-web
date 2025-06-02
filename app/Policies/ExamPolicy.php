<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Exam;
use App\Models\User;
use App\Models\Role;

class ExamPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Exam $exam): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR || $exam->classroom->users->contains($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR || $user->role_id == Role::IS_PENGAJAR;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exam $exam): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR || ($user->role_id == Role::IS_PENGAJAR && $user->id == $exam->created_by);
    }

    /**
     * Determine whether the user can delete the models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Exam $exam): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR || ($user->role_id == Role::IS_PENGAJAR && $user->id == $exam->created_by);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Exam $exam): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Exam $exam): bool
    {
        return $user->role_id == Role::IS_ADMINISTRATOR;
    }
}
