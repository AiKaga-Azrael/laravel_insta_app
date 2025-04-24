<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    # post belongs to a user
    # to get the owner of the post
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    # to get all the categories of a post but only IDs
    public function categoryPost(){
        return $this->hasMany(CategoryPost::class);
    }

    # post has many comments
    # to get all the comments of a post
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    # post has many likes
    # to get all the likes of a post
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    #returns true if the auth user already liked the post
    public function isLiked()
    {
        return $this->likes()->where('user_id', Auth::user()->id)->exists();
    }
}
