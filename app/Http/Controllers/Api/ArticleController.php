<?php

namespace App\Http\Controllers\Api;

use App\Enums\ArticleConstants;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ArticleItemResource;
use App\Http\Resources\Api\ArticleListResource;
use App\Http\Resources\Api\ArticleOptionResource;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * 文章列表
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $query = Article::query()
            ->where('status', ArticleConstants::ARTICLE_STATUS_PUBLISHED);

        $query->when($category = $request->get('category'), function (Builder $query) use ($category) {
            $query->where('category_id', Category::query()->where('title', $category)->first()->id ?? 0);
        });

        $list = $query
            ->latest()
            ->paginate(15);

        return self::page($list, ArticleListResource::class);
    }

    /**
     * 置顶文章
     * @return JsonResponse
     */
    public function tops(): JsonResponse
    {
        $list = Article::query()
            ->where('status', ArticleConstants::ARTICLE_STATUS_PUBLISHED)
            ->where('top', 1)
            ->latest()
            ->take(2)
            ->get();

        return self::items($list, ArticleListResource::class);
    }

    /**
     * 热文排行
     * @return JsonResponse
     */
    public function hots(): JsonResponse
    {
        $list = Article::query()
            ->where('status', ArticleConstants::ARTICLE_STATUS_PUBLISHED)
            ->withCount('comments')
            ->orderByRaw('comments_count DESC')
            ->take(10)
            ->get();

        return self::items($list, ArticleOptionResource::class);
    }

    /**
     * 推荐文章
     * @return JsonResponse
     */
    public function recommends(): JsonResponse
    {
        $list = Article::query()
            ->where('status', ArticleConstants::ARTICLE_STATUS_PUBLISHED)
            ->where('recommend', 1)
            ->latest()
            ->take(10)
            ->get();

        return self::items($list, ArticleOptionResource::class);
    }

    /**
     * 随机数据
     * @return JsonResponse
     */
    public function random()
    {
        $list = Article::query()
            ->where('status', ArticleConstants::ARTICLE_STATUS_PUBLISHED)
            ->orderBy(\DB::raw('RAND()'))
            ->take(10)
            ->get();

        return self::items($list, ArticleOptionResource::class);
    }

    /**
     * 文章详情
     * @param $id
     * @return JsonResponse
     */
    public function detail($id): JsonResponse
    {
        $id = decode_id($id);

        if ($id <= 0 || !($article = Article::query()->where('status', ArticleConstants::ARTICLE_STATUS_PUBLISHED)->find($id))) {
            return self::fail('文章不存在');
        }

        return self::success($article, ArticleItemResource::class);
    }
}
