<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blogroll extends Model
{
    protected $fillable = [
        'title', 'link', 'logo', 'order'
    ];
}
