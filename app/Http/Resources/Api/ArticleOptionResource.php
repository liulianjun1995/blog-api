<?php

namespace App\Http\Resources\Api;

use App\Enums\ArticleConstants;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleOptionResource extends JsonResource
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
            'id'    => encode_id($this->id),
            'title' => $this->title,
        ];
    }
}
