<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFileRequest;
use App\Http\Requests\CreateTemplateRequest;
use App\Http\Requests\ForkTemplateRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\TemplateResource;
use App\Models\Comment;
use App\Models\File;
use App\Models\Project;
use App\Models\Template;
use App\Models\Upvote;
use App\Models\Bookmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class TemplateController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Template::with(['user', 'type']);

        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            foreach ($tags as $tag) {
                $query->whereJsonContains('tags', trim($tag));
            }
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'newest' => $query->orderBy('created_at', 'desc'),
            'most_forked' => $query->orderBy('fork_count', 'desc'),
            default => $query->orderBy('upvote_count', 'desc'),
        };

        $templates = $query->where('visibility', 'public')->paginate($request->get('per_page', 20));

        return TemplateResource::collection($templates);
    }

    public function store(CreateTemplateRequest $request): JsonResponse
    {
        $template = Template::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return response()->json(new TemplateResource($template->load(['user', 'type'])), 201);
    }

    public function show(Request $request, string $templateId): JsonResponse
    {
        $template = Template::with(['user', 'type'])->findOrFail($templateId);

        Gate::authorize('view', $template);

        return response()->json(new TemplateResource($template));
    }

    public function update(UpdateTemplateRequest $request, string $templateId): JsonResponse
    {
        $template = Template::findOrFail($templateId);

        Gate::authorize('update', $template);

        $template->update($request->validated());

        return response()->json(new TemplateResource($template->load(['user', 'type'])));
    }

    public function destroy(Request $request, string $templateId): JsonResponse
    {
        $template = Template::findOrFail($templateId);

        Gate::authorize('delete', $template);

        $template->delete();

        return response()->json(null, 204);
    }

    public function fork(ForkTemplateRequest $request, string $templateId): JsonResponse
    {
        $template = Template::with('files')->findOrFail($templateId);

        Gate::authorize('fork', $template);

        $project = Project::create([
            'user_id' => $request->user()->id,
            'template_id' => $template->id,
            'type_id' => $template->type_id,
            'origin_template_name' => $template->name,
            'name' => $request->validated('name'),
            'visibility' => 'private',
            'tags' => $template->tags,
            'locale' => $template->locale,
            'direction' => $template->direction,
        ]);

        $template->increment('fork_count');

        foreach ($template->files as $file) {
            File::create([
                'project_id' => $project->id,
                'template_id' => $file->template_id,
                'user_id' => $request->user()->id,
                'layer' => $file->layer,
                'name' => $file->name,
                'extension' => $file->extension,
                'sort_order' => $file->sort_order,
                'content' => $file->content,
                'size_bytes' => $file->size_bytes,
            ]);
        }

        return response()->json(new \App\Http\Resources\ProjectResource($project->load(['user', 'type'])), 201);
    }

    public function files(Request $request, string $templateId): AnonymousResourceCollection
    {
        $template = Template::findOrFail($templateId);

        Gate::authorize('view', $template);

        $files = $template->files()->orderBy('sort_order')->get();

        return FileResource::collection($files);
    }

    public function storeFile(CreateFileRequest $request, string $templateId): JsonResponse
    {
        $template = Template::findOrFail($templateId);

        Gate::authorize('update', $template);

        $file = File::create([
            ...$request->validated(),
            'template_id' => $template->id,
            'user_id' => $request->user()->id,
        ]);

        return response()->json(new FileResource($file), 201);
    }

    public function showFile(Request $request, string $templateId, string $fileId): JsonResponse
    {
        $template = Template::findOrFail($templateId);
        $file = $template->files()->findOrFail($fileId);

        Gate::authorize('view', $template);

        return response()->json(new FileResource($file));
    }

    public function updateFile(UpdateFileRequest $request, string $templateId, string $fileId): JsonResponse
    {
        $template = Template::findOrFail($templateId);
        $file = $template->files()->findOrFail($fileId);

        Gate::authorize('update', $template);

        $file->update($request->validated());

        return response()->json(new FileResource($file));
    }

    public function destroyFile(Request $request, string $templateId, string $fileId): JsonResponse
    {
        $template = Template::findOrFail($templateId);
        $file = $template->files()->findOrFail($fileId);

        Gate::authorize('update', $template);

        $file->delete();

        return response()->json(null, 204);
    }

    public function upvote(Request $request, string $templateId): JsonResponse
    {
        $template = Template::findOrFail($templateId);

        $existingUpvote = Upvote::where('user_id', $request->user()->id)
            ->where('target_id', $templateId)
            ->where('target_type', 'template')
            ->first();

        if ($existingUpvote) {
            $existingUpvote->delete();
            $template->decrement('upvote_count');
            $upvoted = false;
        } else {
            Upvote::create([
                'user_id' => $request->user()->id,
                'target_id' => $templateId,
                'target_type' => 'template',
            ]);
            $template->increment('upvote_count');
            $upvoted = true;
        }

        return response()->json([
            'upvoted' => $upvoted,
            'upvote_count' => $template->upvote_count,
        ]);
    }

    public function bookmark(Request $request, string $templateId): JsonResponse
    {
        $template = Template::findOrFail($templateId);

        $existingBookmark = Bookmark::where('user_id', $request->user()->id)
            ->where('target_id', $templateId)
            ->where('target_type', 'template')
            ->first();

        if ($existingBookmark) {
            $existingBookmark->delete();
            $bookmarked = false;
        } else {
            Bookmark::create([
                'user_id' => $request->user()->id,
                'target_id' => $templateId,
                'target_type' => 'template',
            ]);
            $bookmarked = true;
        }

        return response()->json(['bookmarked' => $bookmarked]);
    }

    public function comments(Request $request, string $templateId): AnonymousResourceCollection
    {
        $template = Template::findOrFail($templateId);

        $comments = $template->comments()
            ->with(['user'])
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return \App\Http\Resources\CommentResource::collection($comments);
    }

    public function storeComment(Request $request, string $templateId): JsonResponse
    {
        $template = Template::findOrFail($templateId);

        $validated = $request->validate([
            'body' => 'required|string|min:1',
            'parent_id' => 'nullable|uuid|exists:comments,id',
        ]);

        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'target_id' => $templateId,
            'target_type' => 'template',
            'parent_id' => $validated['parent_id'] ?? null,
            'body' => $validated['body'],
        ]);

        return response()->json(new \App\Http\Resources\CommentResource($comment->load(['user'])), 201);
    }
}
