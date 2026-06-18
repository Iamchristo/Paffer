<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);

        $imagePath = $request->file('image')?->store('posts', 'public');

        $request->user()->posts()->create([
            'body' => $validated['body'],
            'image_path' => $imagePath,
        ]);

        return back()->with('status', 'post-created');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id, 403);

        $post->delete();

        return back()->with('status', 'post-deleted');
    }
}
