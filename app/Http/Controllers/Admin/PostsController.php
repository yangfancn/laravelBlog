<?php

namespace App\Http\Controllers\Admin;

use App\Forms\Admin\PostForm;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index(Request $request, Post $post): View
    {
        if ($search = $request->input('search')) {
            $post = $post->where('title', "%like", $search);
        }
        $data = $post->with('user')->orderByDesc('created_at')->paginate(15);
        return view('admin.lists.posts', compact('data', 'search'));
    }

    public function create(): View
    {
        $form = PostForm::generate(route('admin.posts.store'));
        return $form->create('新建文章');
    }

    public function store(PostRequest $request, Post $post, Tag $tag): JsonResponse
    {
        $data = $request->input();
        if (array_key_exists('created_at', $data) && !$data['created_at']) {
            unset($data['created_at']);
        }
        $post->fill($data);
        try {
            $post->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
        if (array_key_exists('tags', $data) && $data['tags']) {
            $tags = $tag->getOrCreate($data['tags']);
            $post->tags()->sync($tags);
        }
        return new JsonResponse(['message' => '创建文章成功', 'redirect' => route('admin.posts.index')], 200);
    }

    public function edit(Post $post): View
    {
        return PostForm::generate(route('admin.posts.update', $post), 'PUT', $post)->create('编辑文章');
    }

    public function update(PostRequest $request, Post $post, Tag $tag): JsonResponse
    {
        $data = $request->input();
        $post->fill($data);
        try {
            $post->saveOrFail();
        } catch (\Throwable $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
        if (array_key_exists('tags', $data) && $data['tags']) {
            $tags = $tag->getOrCreate($data['tags']);
            $post->tags()->sync($tags);
        }
        return new JsonResponse(['message' => "编辑文章成功"]);
    }

    public function status(Post $post): JsonResponse
    {
        $post->status = !$post->status;
        $post->save();
        return new JsonResponse(['message' => "修改状态成功", 'status' => $post->status]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();
        return new JsonResponse(['message' => '删除文章成功']);
    }
}
