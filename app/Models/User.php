<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    const ADMIN_ROLE_ID = 1;
    const USER_ROLE_ID  = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    # user has many posts
    # to get all the posts of a user
    public function posts()
    {
        return $this->hasMany(Post::class)->latest();
    }

    # one to many
    # user has many followers
    # to get all the followers of a user but only IDs
    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
        // following_id is the reference column. The one who is followed.
    }

    # one to many
    # user has many following
    # to get all the following users but only IDs
    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
        // follower_id is the reference column. The one who is following.
    }

    # will return TRUE if the Auth user is folowing a user
    public function isFollowed()
    {
        return $this->followers()
            ->where('follower_id', Auth::id())
            // ->where('status', 1)
            ->exists();
    }

    public function isFollowedBy(User $user)
    {
        return $this->followers()
            ->where('follower_id', $user->id)
            ->exists();
    }

    public function isPrivate()
    {
        return $this->status == 1;
    }  

    // Users who sent a follow request to the logged in user
    public function pendingFollowers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id')
                    ->wherePivot('status', 0);
    }

    




}
