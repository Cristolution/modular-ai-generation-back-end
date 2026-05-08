<?php

namespace App\Policies;

use App\Models\Template;
use App\Models\User;

class TemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(?User $user, Template $template): bool
    {
        if ($template->visibility === 'public') {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $template->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Template $template): bool
    {
        return $user->id === $template->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }

    public function delete(User $user, Template $template): bool
    {
        return $user->id === $template->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }

    public function fork(User $user, Template $template): bool
    {
        if ($template->visibility === 'public') {
            return true;
        }

        return $user->id === $template->user_id
            || in_array($user->role, ['admin', 'superadmin']);
    }
}
