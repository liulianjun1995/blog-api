<?php

namespace App\Http\Controllers\Api;

use App\Models\Carousel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CarouselController extends ApiController
{
    public function list(Request $request, Carousel $carousel)
    {
        $list = $carousel->query()
            ->select(['title', 'img', 'link'])
            ->latest()
            ->where('status', 1)
            ->take(3)
            ->get();

        return $this->success($list);
    }
}
