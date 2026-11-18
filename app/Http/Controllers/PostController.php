<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        return Post::published()->latest('published_at')->get();
    }

    public function store(StorePostRequest $request)
    {
        $post = $request->user()->posts()->create(
            $request->validated()
        );

        return response()->json($post, 201);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        Gate::authorize('update', $post);

        $post->update($request->validated());

        return $post;
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);

        $post->delete(); // soft delete : deleted_at est rempli

        return response()->noContent();
    }
}
