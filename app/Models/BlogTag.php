<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTag extends Model
{
    use HasFactory;
    protected $table = 'blog_tags';
    protected $guarded = [];
    public function blog()
    {
        return $this->belongsTo(Blog::class,'blog_id');
    }
    public function tag()
    {
        return $this->belongsTo(Tag::class,'tag_id');
    }
}
