<?php

namespace App\Enums;

class ArticleConstants
{
    /**
     * 文章状态 发布
     */
    public const ARTICLE_STATUS_PUBLISHED = 1;
    /**
     * 文章状态 草稿
     */
    public const ARTICLE_STATUS_DRAFT = 2;

    /**
     * 文章状态
     */
    public const ARTICLE_STATUS = [
        self::ARTICLE_STATUS_PUBLISHED => 'published',
        self::ARTICLE_STATUS_DRAFT => 'draft'
    ];

    public static function getArticleStatusLabel($status)
    {
        return self::ARTICLE_STATUS[$status] ?? '';
    }
}
