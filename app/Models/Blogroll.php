<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blogroll extends Model
{
    public $sortable = [
        'sort_field' => 'order',       // 排序字段
        'sort_when_creating' => true,   // 新增是否自增，默认自增
    ];

}
