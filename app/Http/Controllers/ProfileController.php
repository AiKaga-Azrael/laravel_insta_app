<?php

namespace App\Http\Controllers;

use id;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        $viewer = Auth::user();

        $isOwner = $viewer && $viewer->id === $user->id;
        $isFollowing = $user->followers()
                            ->where('follower_id', $viewer->id)
                            ->where('status', 1) // Only accepted followers
                            ->exists();

        if ($user->isPrivate() && !$isOwner && !$isFollowing) {
            return view('users.profile.show', [
                'user' => $user,
                'posts' => [],
                'private' => true,
            ]);
        }

        return view('users.profile.show', [
            'user' => $user,
            'posts' => $user->posts,
            'private' => false,
        ]);

        // $user = $this->user->findOrFail($id);

        // return view('users.profile.show')
        //         ->with('user', $user);
    }

    // edit ()-> display profile edit
    public function edit()
    {
        $id = Auth::user()->id;
        $user = $this->user->findOrFail($id);

        //$user = $this->user->findOrFail(Auth::user()->id);
        
        return view('users.profile.edit')
                    ->with('user', $user);
    }

    // update
    public function update(Request $request)
    {
        #1 validate the data from the form
        $request->validate([
            'name'         => 'required|min:1|max:1000',
            'email'        => 'required|min:1|max:1000',
            'introduction' => 'min:1|max:1000',
            'avatar'       => 'mimes:jpg,png,jpeg,gif|max:1048',#Multipurpose Internet Mail Extensions
            'status'       => 'required'                        
        ]);

        #2 update the profile
        $user = $this->user->findOrFail(Auth::user()->id);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;

        if($request->introduction){
            $user->introduction = $request->introduction;
        }

        if ($request->avatar){
            $user->avatar = 'data:image/' . $request->avatar->extension() . ';base64,' . base64_encode(file_get_contents($request->avatar));
        }

        $user->save();

        return redirect()->route('profile.show', $user->id);
    }

    // followers()- view the followers page of a user
    public function followers($id)
    {
        $user = $this->user->findOrFail($id);

        return view('users.profile.followers')
                ->with('user', $user);
    }

    // following()- view the following page of a user
    public function following($id)
    {
        $user = $this->user->findOrFail($id);

        return view ('users.profile.following')
                ->with('user', $user);
    }






}
