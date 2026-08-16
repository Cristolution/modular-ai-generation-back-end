<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * Admin view of the templates table. Unlike the public
 * `GET /templates` (which only returns `visibility=public`), this
 * endpoint returns every row — including `private` and `unlisted` —
 * so admins can moderate hidden content.
 */
class AdminTemplateController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Template::with(['user', 'type']);

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(fn ($q) => $q
                ->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%"));
        }

        // No visibility filter — admins see everything.
        $query->orderBy('created_at', 'desc');

        $templates = $query->paginate($request->integer('per_page', 20));

        return TemplateResource::collection($templates);
    }
}