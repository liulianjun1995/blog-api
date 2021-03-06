<?php

namespace App\Http\Controllers\Api;

use App\Models\ArticleComment;
use Illuminate\Http\Request;

class CommentController extends ApiController
{
    public function new(Request $request, ArticleComment $comment)
    {
        $field = ['id', 'id as token', 'post_id as post', 'user_id', 'content'];

        $list = $comment->query()
            ->where('show', 1)
            ->select($field)
            ->with(['user:id,nickname,avatar'])
            ->latest()
            ->take(10)
            ->get();

        $list->each(function ($item) {
            $item->token = encode_id($item->token);
            $item->post = encode_id($item->post);
            $item->user->token = encode_id($item->user->id);
            unset($item->id, $item->user_id, $item->user->id);
        });

        return $this->success($list);
    }
}
