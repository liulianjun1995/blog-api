<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Article extends Model
{
    protected $fillable = [
        'title', 'abstract', 'admin_id', 'category_id', 'status', 'cover', 'content', 'top', 'recommend'
    ];

    /**
     * 关联分类
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    /**
     * 关联管理员
     * @return BelongsTo
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    /**
     * 关联评论
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(ArticleComment::class);
    }

    /**
     * 关联赞
     * @return HasMany
     */
    public function praises(): HasMany
    {
        return $this->hasMany(ArticlePraise::class);
    }

    /**
     * 浏览量
     * @return HasMany
     */
    public function visitors(): HasMany
    {
        return $this->hasMany(ArticleVisitorRegistry::class);
    }
}
