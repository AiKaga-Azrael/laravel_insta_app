<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    // store() - save the comment
    public function store(Request $request, $post_id)
    {
        $request->validate(
            [
                'comment_body' => 'required|max:150'
            ],
            [
                'comment_body' . '.required' => 'You cannot submit an empty comment.',
                'comment_body' . '.max' => 'The comment must not have more than 150 characters.'
            ]
        );

        $this->comment->user_id = Auth::user()->id;
        $this->comment->post_id = $post_id;
        $this->comment->body    = $request->input('comment_body');
        $this->comment->save();

        return redirect()->back();
    }

    // delete comment
    public function destroy($id)
    {
       $this->comment->destroy($id);

        # redirect to show post page
        return redirect()->back();
    }
}
