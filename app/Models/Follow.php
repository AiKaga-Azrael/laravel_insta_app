<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    public $timestamps = false;

    # to get the info of a follower like name, email etc...
    // public function follower()
    // {
    //     return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    //     // follower_id is the reference column to get info of the follower.
    // }

    # one to many (inverse)
    # to get the info of the follwing user
    // public function following()
    // {
    //     return $this->belongsTo(User::class, 'following_id')->withTrashed();
    //     // following_id is the reference column.
    // }


    // Specify which columns can be mass-assigned
    protected $fillable = [
        'follower_id',  // Allow mass assignment for follower_id
        'following_id', // Allow mass assignment for following_id
    ];

    # To get the info of a follower like name, email, etc...
    public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id')->withTrashed();
    }

    # One to many (inverse) - to get the info of the following user
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id')->withTrashed();
    }
}






