<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /*return full object*/
    // protected $table = 'articles';

    /*return specified fields*/
    protected $fillable = [
        'title',
        'source',
        'author',
        'thumbnail',
        'content',
        'category',
        'published_at',
    ];
}
