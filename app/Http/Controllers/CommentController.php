<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function update(Request $request, string $commentId): JsonResponse
    {
        $comment = Comment::findOrFail($commentId);

        if ($request->user()->id !== $comment->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'body' => 'required|string|min:1',
        ]);

        $comment->update($validated);

        return response()->json(new CommentResource($comment->load(['user'])));
    }

    public function destroy(Request $request, string $commentId): JsonResponse
    {
        $comment = Comment::findOrFail($commentId);

        if ($request->user()->id !== $comment->user_id) {
            abort(403);
        }

        $comment->delete();

        return response()->json(null, 204);
    }
}