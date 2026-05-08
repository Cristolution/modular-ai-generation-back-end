<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFileRequest;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\ReorderFilesRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\FileResource;
use App\Http\Resources\ProjectResource;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Project::with(['user', 'type'])->where('user_id', $request->user()->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type_id')) {
            $query->where('type_id', $request->type_id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where('name', 'like', "%{$search}%");
        }

        $projects = $query->paginate($request->get('per_page', 20));

        return ProjectResource::collection($projects);
    }

    public function store(CreateProjectRequest $request): JsonResponse
    {
        $project = Project::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return (new ProjectResource($project->load(['user', 'type'])))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, string $projectId): ProjectResource
    {
        $project = Project::with(['user', 'type'])->findOrFail($projectId);

        Gate::authorize('view', $project);

        return new ProjectResource($project);
    }

    public function update(UpdateProjectRequest $request, string $projectId): ProjectResource
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('update', $project);

        $project->update($request->validated());

        return new ProjectResource($project->load(['user', 'type']));
    }

    public function destroy(Request $request, string $projectId): JsonResponse
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('delete', $project);

        $project->delete();

        return response()->json(null, 204);
    }

    public function files(Request $request, string $projectId): AnonymousResourceCollection
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('view', $project);

        $files = $project->files()->orderBy('sort_order')->get();

        return FileResource::collection($files);
    }

    public function storeFile(CreateFileRequest $request, string $projectId): JsonResponse
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('update', $project);

        $file = File::create([
            ...$request->validated(),
            'project_id' => $project->id,
            'user_id' => $request->user()->id,
        ]);

        return (new FileResource($file))
            ->response()
            ->setStatusCode(201);
    }

    public function showFile(Request $request, string $projectId, string $fileId): FileResource
    {
        $project = Project::findOrFail($projectId);
        $file = $project->files()->findOrFail($fileId);

        Gate::authorize('view', $project);

        return new FileResource($file);
    }

    public function updateFile(UpdateFileRequest $request, string $projectId, string $fileId): FileResource
    {
        $project = Project::findOrFail($projectId);
        $file = $project->files()->findOrFail($fileId);

        Gate::authorize('update', $project);

        $file->update($request->validated());

        return new FileResource($file);
    }

    public function destroyFile(Request $request, string $projectId, string $fileId): JsonResponse
    {
        $project = Project::findOrFail($projectId);
        $file = $project->files()->findOrFail($fileId);

        Gate::authorize('update', $project);

        $file->delete();

        return response()->json(null, 204);
    }

    public function reorderFiles(ReorderFilesRequest $request, string $projectId): AnonymousResourceCollection
    {
        $project = Project::findOrFail($projectId);

        Gate::authorize('update', $project);

        $order = $request->validated()['order'];

        foreach ($order as $index => $fileId) {
            File::where('id', $fileId)->where('project_id', $project->id)->update(['sort_order' => $index]);
        }

        $files = $project->files()->orderBy('sort_order')->get();

        return FileResource::collection($files);
    }
}
