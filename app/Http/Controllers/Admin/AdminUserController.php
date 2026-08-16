<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRoleRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminUserController extends Controller
{
    /**
     * GET /api/v1/admin/users
     * Lists every user in the system. Admin-only. Supports ?q= for a
     * keyword search on name and email, plus ?page / ?per_page for
     * pagination (handled by Laravel's paginator — AnonymousResourceCollection
     * surfaces the standard `meta` block).
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::query();

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        // Newest first — admins typically want the most recent signups at
        // the top when triaging accounts.
        $query->orderBy('created_at', 'desc');

        $users = $query->paginate($request->integer('per_page', 20));

        return UserResource::collection($users);
    }

    /**
     * PUT /api/v1/admin/users/{user_id}
     * Update a user's role. The body MUST contain `role` ∈ {user, admin};
     * any other field is rejected by UpdateUserRoleRequest so admins
     * can't accidentally overwrite email/password from here.
     */
    public function updateRole(UpdateUserRoleRequest $request, string $userId): UserResource
    {
        $user = User::findOrFail($userId);

        $user->update(['role' => $request->validated('role')]);

        // Return the resource directly so Laravel's JsonResource layer
        // auto-wraps the payload in `{ data: { ... } }`, matching the
        // shape of the index endpoint and the public UserController.
        return new UserResource($user->refresh());
    }
}