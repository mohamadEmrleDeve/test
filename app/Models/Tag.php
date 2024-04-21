<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    protected $table = 'tags';
    protected $guarded = [];
    public function blog_tag()
    {
        return $this->hasMany(BlogTag::class,'blog_id');
    }
}
