<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OssController extends Controller
{
    /**
     * oss 签名
     * @param Request $request
     * @return JsonResponse
     */
    public function signature(Request $request): JsonResponse
    {
        $dir = $request->post('dir');

        $config = $this->signatureConfig($dir);

        return $this->success($config);
    }
}
