<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    private $follow;

    public function __construct(Follow $follow)
    {
        $this->follow = $follow;
    }

    public function store($user_id)
    {
        $user = User::findOrFail($user_id);

        // Don't allow sending multiple requests
        $exists = Follow::where('follower_id', Auth::id())
                        ->where('following_id', $user_id)
                        ->first();

        if ($exists) {
            return back(); // already sent
        }

        Follow::create([
            'follower_id' => Auth::id(),
            'following_id' => $user_id,
            'status' => $user->isPrivate() ? 0 : 1,
        ]);

        return redirect()->back();
    }

    public function destroy($user_id)
    {
        Follow::where('follower_id', Auth::id())
            ->where('following_id', $user_id)
            ->delete();

        return redirect()->back();
    }

    public function friendRequests()
    {
        $requests = Auth::user()->pendingFollowers()->get();
        return view('users.profile.request', compact('requests'));
    }

    public function acceptRequest($follower_id)
    {
        Follow::where('follower_id', $follower_id)
            ->where('following_id', Auth::id())
            ->update(['status' => 1]);
        return back();
    }

    public function rejectRequest($follower_id)
    {
        Follow::where('follower_id', $follower_id)
            ->where('following_id', Auth::id())
            ->delete();
        return back();
    }

}
