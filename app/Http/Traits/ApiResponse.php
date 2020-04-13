<?php

namespace App\Http\Traits;

use App\Http\Resources\BaseResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

trait ApiResponse
{
    protected static function success($data = [], $class = null)
    {
        if ($class !== null) {
            $data = new $class($data);
        }

        return response()->json([
            'code' => 20000,
            'data' => $data
        ]);
    }

    protected static function redirect($url)
    {
        return response()->json([
            'code' => 30000,
            'url' => $url
        ]);
    }

    protected static function fail($msg, $code = 20001)
    {
        return response()->json([
            'code' => $code,
            'message' => $msg
        ]);
    }

    protected static function items($list, $class = BaseResource::class)
    {
        $items = [];

        foreach ($list as $item) {
            $items[] = new $class($item);
        }

        return self::success($items);
    }

    protected static function page(LengthAwarePaginator $paginateData, $class = BaseResource::class)
    {
        $items = [];

        foreach ($paginateData->items() as $item) {
            $items[] = new $class($item);
        }

        $res = [
            'code' => 20000,
            'items' => $items,
            'pageSize' => $paginateData->perPage(),
            'currentPage' => $paginateData->currentPage(),
            'total' => $paginateData->total(),
            'totalPage' => $paginateData->lastPage()
        ];

        return response()->json($res);
    }
}
