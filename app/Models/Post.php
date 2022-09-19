<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'slug'];

    public function categories(){
        return $this->belongsToMany(BlogCategory::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
