<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagsController extends BaseController
{
    public function list(Request $request, Tag $tag): JsonResponse
    {
        $where = [];
        if ($query = $request->input('q', null)) {
            $where[] = ['name', 'like', "%$query%"];
        }
        $tags = $tag->where($where)->limit(30)->get(['name as text'])->toArray();
        $tags = array_map(function ($item) {
            $item['id'] = $item['text'];
            return $item;
        }, $tags);
        return new JsonResponse(['results' => $tags], 200);
    }
}
