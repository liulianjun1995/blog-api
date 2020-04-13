<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ArticleConstants;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ArticleListResource;
use App\Models\Article;
use App\Validates\ArticleValidate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /**
     * 文章列表
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $query = Article::query();

        $query->when($request->get('category_id') > 0, function (Builder $query) use ($request) {
            $query->where('category_id', $request->get('category_id'));
        });

        $query->when($request->get('keyword'), function (Builder $query) use ($request) {
            $like = '%' . $request->get('keyword') . '%';
            $query->where('title', 'like', $like);
        });

        $list = $query
            ->latest()
            ->paginate(15);

        return self::page($list, ArticleListResource::class);
    }

    /**
     * 文章详情
     * @param $id
     * @return JsonResponse
     */
    public function detail($id)
    {
        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return self::fail('文章不存在');
        }

        return self::success($article);
    }

    /**
     * 创建文章
     * @param Request $request
     * @param ArticleValidate $validate
     * @return JsonResponse
     */
    public function create(Request $request, ArticleValidate $validate): JsonResponse
    {
        $params = $request->only(['title', 'abstract', 'category_id', 'tags', 'status', 'cover', 'content']);

        $result = $validate->store($params);

        if ($result !== true) {
            return self::fail($result);
        }

        $params['admin_id'] = Auth::guard('admin')->id();
        Article::query()->create($params);

        return self::success();
    }

    /**
     * 更新文章
     * @param Request $request
     * @param $id
     * @param ArticleValidate $validate
     * @return JsonResponse
     */
    public function update(Request $request, $id, ArticleValidate $validate)
    {
        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return self::fail('文章不存在');
        }

        $params = $request->only(['title', 'abstract', 'user_id', 'category_id', 'status', 'cover', 'tags', 'content']);

        $result = $validate->update($id, $params);

        if ($result !== true) {
            return self::fail($result);
        }

        if ($article->fill($params)->save() === false) {
            return self::fail('更新失败');
        }

        return self::success();
    }

    /**
     * 更新属性
     * @param Request $request
     * @param $id
     * @param ArticleValidate $validate
     * @return JsonResponse
     */
    public function profile(Request $request, $id, ArticleValidate $validate): JsonResponse
    {
        $key = $request->post('key');
        $value = $request->post('value');

        if (!in_array($key, ['status', 'top', 'recommend'])) {
            return self::fail('参数异常');
        }

        if ($id <= 0 || !($article = Article::query()->find($id))) {
            return self::fail('文章不存在');
        }

        if ($key === 'status') {
            switch ($value) {
                case 'published':
                    $value = ArticleConstants::ARTICLE_STATUS_PUBLISHED;
                    break;
                case 'draft':
                    $value = ArticleConstants::ARTICLE_STATUS_DRAFT;
                    break;
                default :
                    return self::fail('参数异常');
            }
        }

        $params = [
            $key => $value
        ];

        $result = $validate->update($id, $params);

        if ($result !== true) {
            return self::fail($result);
        }

        if ($article->fill($params)->save() === false) {
            return self::fail('更新失败');
        }

        return self::success();
    }

}
