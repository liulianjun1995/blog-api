<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * 分类选项列表
     * @param Request $request
     * @return JsonResponse
     */
    public function options()
    {
        $list = Category::query()
            ->select(['id', 'title'])
            ->orderBy('order')
            ->get();

        return self::success($list);
    }

    /**
     * 分类列表
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $list = Category::query()
            ->orderBy('order')
            ->select(['id', 'title', 'order', 'show'])
            ->get();

        return self::success($list);
    }

    /**
     * 分类排序
     * @param Request $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function order(Request $request)
    {
        $list = $request->post('list', []);

        if (count($list) === 0) {
            return self::fail('参数异常');
        }

        $categoryList = Category::query()
            ->whereIn('id', array_column($list, 'id'))
            ->select(['id', 'title', 'order', 'show'])
            ->get();

        if (count($list) !== $categoryList->count()) {
            return self::fail('参数异常');
        }

        DB::beginTransaction();

        try {
            foreach ($list as $key => $category) {
                $key++;
                $cat = $categoryList->where('id', $category['id'])->first();
                $cat->order = $key;
                $cat->save();
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            return self::fail('操作失败');
        }

        DB::commit();

        return self::success();
    }
}
