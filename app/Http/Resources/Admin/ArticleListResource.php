<?php

namespace App\Http\Resources\Admin;

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
            'id'        => $this->id,
            'title'     => $this->title,
            'abstract'  => $this->abstract,
            'cover'     => $this->cover,
            'category'  => $this->category->title ?? '',
            'author'    => $this->admin->username ?? '',
            'status'    => ArticleConstants::getArticleStatusLabel($this->status),
            'views'     => $this->views,
            'top'       => $this->top,
            'recommend' => $this->recommend,
            'created_at'=> $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
