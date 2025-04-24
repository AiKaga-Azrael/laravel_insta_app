<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostsController extends Controller
{
    private $post;
    private $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    # index() - view the Admin: posts page
    public function index()
    {
        //wtihTrashed() - Include the soft deleted records in a query's result
        $all_posts = $this->post->withTrashed()->latest()->paginate(5);
    
        return view('admin.posts.index')
                ->with('all_posts', $all_posts);
    }

    # hide() - to soft delete the post
    public function deactivate($id)
    {
        $this->post->destroy($id);

        return redirect()->back();
    }

    # activate() undelete SofDeletes column(deleted_at) back to NULL
    public function activate($id)
    {
        // onlyTrashed() - retrieves soft deleted records only.
        // restore() - This will "un-delete" a soft deleted model. This will set the "deleted_at" column to null
        $this->post->onlyTrashed()->findOrFail($id)->restore();

        return redirect()->back();
    }
}
