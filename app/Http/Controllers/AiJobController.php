<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateFullRequest;
use App\Http\Requests\GenerateLayerRequest;
use App\Http\Resources\AiJobResource;
use App\Models\AiJob;
use App\Models\File;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AiJobController extends Controller
{
    public function index(Request $request, string $projectId): AnonymousResourceCollection
    {
        $project = $request->user()->projects()->findOrFail($projectId);

        $jobs = $project->aiJobs()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return AiJobResource::collection($jobs);
    }

    public function show(Request $request, string $jobId): AiJobResource
    {
        $job = AiJob::whereHas('project', fn ($q) => $q->where('user_id', $request->user()->id))
            ->orWhereHas('template', fn ($q) => $q->where('user_id', $request->user()->id))
            ->findOrFail($jobId);

        return new AiJobResource($job);
    }

    public function generateFull(GenerateFullRequest $request, string $projectId): JsonResponse
    {
        $project = $request->user()->projects()->findOrFail($projectId);

        $provider = $request->user()->aiProviders()->findOrFail($request->validated('provider_id'));

        $job = AiJob::create([
            'project_id' => $project->id,
            'template_id' => $project->template_id,
            'triggered_by' => $request->user()->id,
            'provider_id' => $provider->id,
            'provider' => $provider->provider,
            'model' => $request->validated('model') ?? $provider->default_model,
            'prompt' => $request->validated('prompt'),
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return (new AiJobResource($job))
            ->response()
            ->setStatusCode(202);
    }

    public function generateLayer(GenerateLayerRequest $request, string $projectId, string $fileId): JsonResponse
    {
        $project = $request->user()->projects()->findOrFail($projectId);

        $file = $project->files()->findOrFail($fileId);

        $provider = $request->user()->aiProviders()->findOrFail($request->validated('provider_id'));

        $job = AiJob::create([
            'file_id' => $file->id,
            'project_id' => $project->id,
            'template_id' => $project->template_id,
            'triggered_by' => $request->user()->id,
            'provider_id' => $provider->id,
            'provider' => $provider->provider,
            'model' => $request->validated('model') ?? $provider->default_model,
            'layer' => $file->layer,
            'prompt' => $request->validated('prompt'),
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return (new AiJobResource($job))
            ->response()
            ->setStatusCode(202);
    }
}