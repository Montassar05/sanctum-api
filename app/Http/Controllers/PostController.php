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
        // 15 posts par page, jamais toute la table.
        return Post::published()
            ->latest('published_at')
            ->paginate(15);
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

    public function publish(Post $post)
    {
        Gate::authorize('update', $post);

        $post->update(['published_at' => now()]);

        return $post;
    }
}
