<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    private $post;
    private $user;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Post $post, User $user)
    {
       $this->post = $post;
       $this->user = $user;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $home_posts = $this->getHomePosts();
        $suggested_users = $this->getSuggestedUsers();
        // $liked_users = $this->getLikedUsers();

        return view('users.home')
               ->with('home_posts', $home_posts)
               ->with('suggested_users', $suggested_users);
    }

    # get the posts of the auth user and the posts of the following users
    private function getHomePosts()
    {
        $all_posts = $this->post->latest()->get();
        $home_posts = []; 

        foreach ($all_posts as $post){
            if ($post->user->isFollowed() || $post->user->id === Auth::user()->id){$home_posts[] = $post;}
            }
            return $home_posts;
    }

    # getSuggestedUsers()- Get the users that the Auth user is not following
    # if (!$user->isFollowed()){ is if the user is not following
    private function getSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = []; 

        foreach ($all_users as $user){
            if (!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return array_slice($suggested_users, 0, 5);
        // array_slice(x,y,z)
        // x -- array
        // y -- offset/starting index
        // z -- length/how many
    }

    public function showSuggestedUsers()
    {
        $all_users = $this->user->all()->except(Auth::user()->id);
        $suggested_users = [];

        foreach($all_users as $user){
            if (!$user->isFollowed()){
                $suggested_users[] = $user;
            }
        }

        return view('users.suggestions')
                ->with('suggested_users', $suggested_users);
    }

    public function search(Request $request)
    {
        $users = $this->user->where('name', 'like', '%' . $request->search . '%')->get();
        //'name' is column from users table 
        //'like' is operator in SQL used for pattern matching
        // '%' . $request->search . '%' -> matches anywhere in the name. search is the name from the form on app.blade.php

        return view('users.search')
                ->with('users', $users)
                ->with('search', $request->search);
    }

}
