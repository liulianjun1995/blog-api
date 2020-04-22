<?php

namespace App\Http\Resources\Api;

use App\Enums\ArticleConstants;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'                => encode_id($this->id),
            'title'             => $this->title,
            'abstract'          => $this->abstract,
            'cover'             => $this->cover,
            'category'          => $this->category->title ?? '',
            'views'             => $this->visitors->sum('clicks'),
            'comments_count'    => $this->comments->count(),
            'praises'           => $this->praises->count(),
            'recommend'         => (boolean)$this->recommend,
            'created_at'        => $this->created_at->format('Y-m-d'),
        ];
    }
}
