<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ResourceResource;
use App\Http\Resources\TemplateResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $users = User::all();
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request): UserResource
    {
        $validated = $request->validated();
        $validated['password_hash'] = Hash::make($validated['password']);
        unset($validated['password']);

        $user = User::create($validated);

        return new UserResource($user);
    }

    public function show(string $id): UserResource
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $id): UserResource
    {
        $user = User::findOrFail($id);
        $validated = $request->validated();

        if (isset($validated['password'])) {
            $validated['password_hash'] = Hash::make($validated['password']);
            unset($validated['password']);
        }

        $user->update($validated);

        return new UserResource($user);
    }

    public function destroy(string $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }

    public function templates(string $userId): AnonymousResourceCollection
    {
        $user = User::findOrFail($userId);

        $templates = $user->templates()
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return TemplateResource::collection($templates);
    }

    public function resources(string $userId): AnonymousResourceCollection
    {
        $user = User::findOrFail($userId);

        $resources = $user->resources()
            ->where('visibility', 'public')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return ResourceResource::collection($resources);
    }

    public function projects(Request $request, string $userId): AnonymousResourceCollection
    {
        $user = User::findOrFail($userId);

        // Owners see every project (including private drafts); everyone else
        // sees only projects the user has marked public.
        $query = $user->projects()->with('type');
        $isOwner = $request->user() && $request->user()->id === $user->id;
        if (!$isOwner) {
            $query->where('visibility', 'public');
        }
        $projects = $query->orderBy('created_at', 'desc')->paginate(20);

        return \App\Http\Resources\ProjectResource::collection($projects);
    }
}
