<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ResourceResource;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Admin view of the community resources table. Like AdminTemplateController,
 * this intentionally omits the `visibility=public` filter so admins can
 * review every row regardless of the author's privacy setting.
 */
class AdminResourceController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Resource::with(['user']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        $query->orderBy('created_at', 'desc');

        $resources = $query->paginate($request->integer('per_page', 20));

        return ResourceResource::collection($resources);
    }
}