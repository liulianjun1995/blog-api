<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends ApiController
{
    public function index(Request $request, Post $posts)
    {
        $fields = ['id', 'id as token', 'title', 'abstract', 'category_id', 'cover', 'content', 'recommend', 'top', 'views', 'created_at'];
        $list =  $posts->select($fields)->withCount('comments')->withCount('praises')
            ->with(['tags:title', 'category:id,title'])->latest()->paginate(10);
        $list->each(function ($item) {
           $item->token = encode_id($item->token);
           $item->cover = Storage::disk(config('admin.upload.disk'))->url($item->cover);
           $item->create_time = $item->created_at->format('Y-m-d');
           unset($item->created_at);
           unset($item->id);
        });
        $list = $list->toArray();
        $list['comment_count'] = PostComment::query()->count();
        return $this->success($list);
    }

    public function top(Request $request, Post $posts)
    {
        $fields = ['id', 'id as token', 'title', 'abstract', 'category_id', 'cover', 'content', 'recommend', 'top', 'views', 'created_at'];

        $list =  $posts->select($fields)
            ->withCount('comments')
            ->withCount('praises')
            ->with(['tags:title', 'category:id,title'])
            ->where('top', 1)
            ->orderBy('created_at', 'desc')
            ->take(2)
            ->get();
        $list->each(function ($item) {
            $item->token = encode_id($item->token);
            $item->cover = Storage::disk(config('admin.upload.disk'))->url($item->cover);
            $item->create_time = $item->created_at->format('Y-m-d');
            unset($item->created_at);
            unset($item->id);
        });

        return $this->success($list);
    }

    public function recommend(Request $request, Post $posts)
    {
        $fields = ['id as token', 'title'];
        $list =  $posts->query()->where('recommend', 1)
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get($fields);
        $list->each(function ($item) {
            $item->token = encode_id($item->token);
        });
        return $this->success($list);
    }

    public function hot(Request $request, Post $posts)
    {
        $fields = ['id as token', 'title'];
        $list =  $posts->query()
            ->orderBy('views', 'desc')
            ->take(8)
            ->get($fields);
        $list->each(function ($item) {
            $item->token = encode_id($item->token);
        });
        return $this->success($list);
    }

    public function show(Request $request, Post $posts, $token)
    {
        $id = decode_id($token);
        $fields = ['id', 'id as token', 'title', 'abstract', 'category_id', 'cover', 'content', 'recommend', 'top', 'views', 'created_at'];
        if ($id <= 0 || !($info = $posts->query()->select($fields)->withCount('comments')->withCount('praises')->with(['tags:title', 'category:id,title'])->find($id))) {
            return $this->error('文章不存在');
        }
        $info->token = encode_id($info->token);
        $info->increment('views');

        $comments = [];

        $info->comments = $comments;

        unset($info->id, $info->category->id, $info->category_id);
        return $this->success($info);
    }


}
