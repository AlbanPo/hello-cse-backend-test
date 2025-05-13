<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function store(StoreCommentRequest $request, Profile $profile): JsonResponse
    {
        /** @var Admin $admin */
        $admin = $request->user();

        $comment = Comment::create([
            'admin_id' => $admin->id,
            'profile_id' => $profile->id,
            'content' => $request->validated('content'),
        ]);

        return response()->json($comment, 201);
    }
}
