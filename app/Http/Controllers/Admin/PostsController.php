<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\PostForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class PostsController extends Controller
{
    public function index()
    {

    }

    public function create(): View
    {
        $form = PostForm::generate(route('admin.posts.store'));
        return $form->create('新建文章');
    }

    public function store(PostRequest $request, Post $post, Tag $tag): JsonResponse
    {
        $data = $request->validated();
        $post->fill($data);
        try {
            $post->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
        if ($data['tags']) {
            $tags = $tag->getOrCreate($data['tags']);
            $post->tags()->sync($tags);
        }
        return new JsonResponse(['message' => __('add post success'), 'redirect' => route('admin.posts.index')], 200);
    }
}
