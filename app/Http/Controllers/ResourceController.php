<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateResourceRequest;
use App\Http\Requests\ForkResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Http\Resources\ResourceResource;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\Resource;
use App\Models\Upvote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ResourceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Resource::with(['user']);

        if ($request->filled('kind')) {
            $query->where('kind', $request->kind);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'newest' => $query->orderBy('created_at', 'desc'),
            'most_forked' => $query->withCount('forks')->orderBy('forks_count', 'desc'),
            default => $query->withCount('upvotes')->orderBy('upvotes_count', 'desc'),
        };

        $resources = $query->where('visibility', 'public')->paginate($request->get('per_page', 20));

        return ResourceResource::collection($resources);
    }

    public function store(CreateResourceRequest $request): JsonResponse
    {
        $resource = Resource::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return (new ResourceResource($resource->load(['user'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, string $resourceId): ResourceResource
    {
        $resource = Resource::with(['user'])->findOrFail($resourceId);

        if ($resource->visibility !== 'public' && $resource->user_id !== $request->user()?->id) {
            abort(403);
        }

        return new ResourceResource($resource);
    }

    public function update(UpdateResourceRequest $request, string $resourceId): ResourceResource
    {
        $resource = Resource::findOrFail($resourceId);

        if ($request->user()->id !== $resource->user_id) {
            abort(403);
        }

        $resource->update($request->validated());

        return new ResourceResource($resource->load(['user']));
    }

    public function destroy(Request $request, string $resourceId): JsonResponse
    {
        $resource = Resource::findOrFail($resourceId);

        if ($request->user()->id !== $resource->user_id) {
            abort(403);
        }

        $resource->delete();

        return response()->json(null, 204);
    }

    public function fork(ForkResourceRequest $request, string $resourceId): JsonResponse
    {
        $original = Resource::findOrFail($resourceId);

        if ($original->visibility !== 'public' && $original->user_id !== $request->user()->id) {
            abort(403);
        }

        $fork = Resource::create([
            'user_id' => $request->user()->id,
            'forked_from_id' => $original->id,
            'kind' => $original->kind,
            'name' => $request->validated('name') ?? 'Fork of ' . $original->name,
            'description' => $original->description,
            'content' => $original->content,
            'placeholders' => $original->placeholders,
            'visibility' => 'private',
            'tags' => $original->tags,
        ]);

        return (new ResourceResource($fork->load(['user'])))
            ->response()
            ->setStatusCode(201);
    }

    public function forks(Request $request, string $resourceId): AnonymousResourceCollection
    {
        $original = Resource::findOrFail($resourceId);

        $forks = $original->forks()
            ->with(['user'])
            ->where('visibility', 'public')
            ->paginate($request->get('per_page', 20));

        return ResourceResource::collection($forks);
    }

    public function upvote(Request $request, string $resourceId): JsonResponse
    {
        $resource = Resource::findOrFail($resourceId);

        $existingUpvote = Upvote::where('user_id', $request->user()->id)
            ->where('target_id', $resourceId)
            ->where('target_type', 'resource')
            ->first();

        if ($existingUpvote) {
            $existingUpvote->delete();
            $upvoted = false;
        } else {
            Upvote::create([
                'user_id' => $request->user()->id,
                'target_id' => $resourceId,
                'target_type' => 'resource',
            ]);
            $upvoted = true;
        }

        return response()->json([
            'upvoted' => $upvoted,
            'upvote_count' => $resource->upvotes()->count(),
        ]);
    }

    public function bookmark(Request $request, string $resourceId): JsonResponse
    {
        $resource = Resource::findOrFail($resourceId);

        $existingBookmark = Bookmark::where('user_id', $request->user()->id)
            ->where('target_id', $resourceId)
            ->where('target_type', 'resource')
            ->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => $request->user()->id,
                'target_id' => $resourceId,
                'target_type' => 'resource',
            ]);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }

    public function comments(Request $request, string $resourceId): AnonymousResourceCollection
    {
        $resource = Resource::findOrFail($resourceId);

        $comments = $resource->comments()
            ->with(['user'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return \App\Http\Resources\CommentResource::collection($comments);
    }

    public function storeComment(Request $request, string $resourceId): JsonResponse
    {
        $resource = Resource::findOrFail($resourceId);

        if ($resource->visibility !== 'public' && $resource->user_id !== $request->user()->id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|min:1',
            'parent_id' => 'nullable|uuid|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'target_id' => $resourceId,
            'target_type' => 'resource',
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        return (new \App\Http\Resources\CommentResource($comment->load(['user'])))
            ->response()
            ->setStatusCode(201);
    }
}