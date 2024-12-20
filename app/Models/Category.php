<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'parent_id', 'created_by'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function newsPosts()
    {
        return $this->belongsToMany(NewsPost::class, 'category_news_post');
    }
}
