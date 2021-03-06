<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleVisitorRegistry extends Model
{
    protected $table = 'article_visitor_registry';

    protected $fillable = [
        'article_id', 'ip', 'country', 'clicks'
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
