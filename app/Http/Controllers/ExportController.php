<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExportJobRequest;
use App\Http\Resources\ExportJobResource;
use App\Models\ExportJob;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function store(CreateExportJobRequest $request, string $projectId): JsonResponse
    {
        $project = Project::findOrFail($projectId);

        if ($project->user_id !== $request->user()->id) {
            abort(403);
        }

        $exportJob = ExportJob::create([
            'project_id' => $project->id,
            'format' => $request->validated('format'),
            'options' => $request->validated('options'),
            'status' => 'pending',
        ]);

        return response()->json(new ExportJobResource($exportJob), 202);
    }

    public function show(string $jobId): JsonResponse
    {
        $exportJob = ExportJob::findOrFail($jobId);

        return response()->json(new ExportJobResource($exportJob));
    }
}