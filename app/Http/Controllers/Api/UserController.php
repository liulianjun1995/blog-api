<?php

namespace App\Http\Controllers\Api;

use App\Models\ArticlePraise;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends ApiController
{
    public function user()
    {
        $user = Auth::guard('api')->user()->only(['nickname', 'avatar']);
        return $this->success($user);
    }

    public function comment(Request $request, Article $posts, $token)
    {
        $post_id = decode_id($token);

        if ($post_id <= 0 || !($post = $posts->query()->find($post_id))) {
            return $this->error('文章不存在！');
        }
        if (!($content = trim($request->input('content')))) {
            return $this->error('请输入评论内容');
        }
        if ($comment = $post->comments()->create([
            'user_id'   => Auth::guard('api')->id(),
            'content'   => $content,
            'parent_id'    => 0,
            'root_id'    => 0,
        ])) {
            return $this->success([
                'token'     => encode_id($comment->id),
                'parent'    => encode_id($comment->parent_id),
                'content'   => $comment->content,
                'root'      => encode_id($comment->root_id),
                'user'      => [
                    'token'     => encode_id(Auth::guard('api')->id()),
                    'avatar'    => Auth::guard('api')->avatar,
                    'nickname'  => Auth::guard('api')->nickname,
                ],
                'created_at'    => $comment->created_at->format('Y-m-d H:i:s')
            ]);
        }
        return $this->error('系统异常');
    }

    public function reply(Request $request, Comments $comments, $token)
    {
        $comment_id = decode_id($token);
        if ($comment_id <=0 || !($comment = $comments->query()->find($comment_id))) {
            return $this->error('评论不存在！');
        }
        if (!($content = trim($request->input('content')))) {
            return $this->error('请输入评论内容');
        }
        $reply = $comments->query()->create([
            'parent_id' => $comment_id,
            'content'   => htmlspecialchars($content),
            'user_id'   => Auth::guard('api')->id(),
            'root_id'   => $comment->root_id ?:  $comment->id,
            'post_id'   => $comment->post_id
        ]);
        return $this->success([
            'token'     => encode_id($reply->id),
            'parent'    => encode_id($reply->parent_id),
            'content'   => $reply->content,
            'root'      => encode_id($reply->root_id),
            'user'      => [
                'token'     => encode_id(Auth::guard('api')->id()),
                'avatar'    => Auth::guard('api')->user()->avatar,
                'nickname'  => Auth::guard('api')->user()->nickname,
            ],
            'created_at'    => $reply->created_at
        ]);
    }

    public function praise(Request $request, Article $posts, $token)
    {
        $id = decode_id($token);
        if ($id <= 0 || !($post = $posts->query()->find($id))) {
            return $this->error('文章不存在');
        }
        if (ArticlePraise::query()->where('user_id', Auth::guard('api')->id())->where('post_id', $id)->exists()) {
            return $this->error('你已点过赞了');
        }
        if ($post->praises()->create([
            'user_id'   => Auth::guard('api')->id(),
            'ip'        => $request->ip(),
        ])) {
            return $this->success();
        }
        return $this->error('系统异常');
    }
}
