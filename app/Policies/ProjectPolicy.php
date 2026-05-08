<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Project $project): bool
    {
        if ($project->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $project->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }
}
