<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $post;
    private $category;

    public function __construct(Post $post, Category $category)
    {
        $this->post = $post;
        $this->category = $category;
    }

    # create() - view the create post page
    public function create()
    {
        $all_categories = $this->category->all();// retrieves all categories

        return view ('users.posts.create')
                ->with('all_categories', $all_categories);
    }

    # store() - save the post to DB
    public function store(Request $request)
    {
        $request->validate([
            'category'     => 'required|array|between:1,3',
            'description'  => 'required|max:1000',
            'image'        => 'required|mimes:jpg,jpeg,png,gif|max:1048'
                                        #Multipurpose Internet Mail Extensions                    
        ]);

        #save the post
        $this->post->user_id        = Auth::user()->id;
                                        # syntax: data:[content]/[type];base64,
        $this->post->image          = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        $this->post->description    = $request->description;
        $this->post->save();

        #save the categories to the category_post pivot table
        foreach ($request->category as $category_id){
            $category_post[] = ['category_id' => $category_id];
            /*
                    $category_post = [
                        ['category_id  => 1],
                        ['category_id  => 2],
                        ['category_id  => 3]
                    ];
                    *This is 2D associative array; theres an array inside of an array
            */
        }
        $this->post->categoryPost()->createMany($category_post);
            /* createMany is only for 2D associative array
                ['post_id' => 1, 'category_id' => 1],
                ['post_id' => 1, 'category_id' => 2],
                ['post_id' => 1, 'category_id' => 3],
            */

        #Got back to homepage
        return redirect()->route('index');
    }

    # show() - view Show Post Page
    public function show($id)
    {
        $post = $this->post->findOrFail($id);

        return view('users.posts.show')
                ->with('post', $post);
    }

    // edit() - view the dit post page and display details of a post
    public function edit($id)
    {
        $post = $this->post->findOrFail($id);

        # if the Auth user is NOT the owner of the post, redirect to homepage
        if (Auth::user()->id != $post->user->id){
            return redirect()->route('index');
        }

        $all_categories = $this->category->all(); //retrieves all categories

        # get all the category IDs of this post. Save in array
        $selected_categories = [];
        foreach ($post->categoryPost as $category_post) {
            $selected_categories[] = $category_post->category_id;
            /*
                $selected_categories = [
                    [1]
                    [2]
                    [3]
                ];
            */
        }

        return view('users.posts.edit')
                ->with('post', $post)
                ->with('all_categories', $all_categories)
                ->with('selected_categories', $selected_categories);
    }

    // update()- update the post
    public function update(Request $request, $id)
    {
        #1 validate the data from the form
        $request->validate([
            'category'    => 'required|array|between:1,3',
            'description' => 'required|min:1|max:1000',
            'image'       => 'mimes:jpg,png,jpeg,gif|max:1048'
                                #Multipurpose Internet Mail Extensions
        ]);

        #2 update the post
        $post              = $this->post->findOrFail($id);
        $post->description = $request->description;

        //if there is a new image
        if ($request->image){
            $post->image = 'data:image/' . $request->image->extension() . ';base64,' . base64_encode(file_get_contents($request->image));
        }

        $post->save();

        #3 delete all records from categoryPost related to this post
        $post->categoryPost()->delete();

        #4 save the new categories to category_post pivot table
        foreach ($request->category as $category_id) {
            $category_post[] = [
                'category_id' => $category_id
            ];
        }
        $post->categoryPost()->createMany($category_post);

        # redirect to show post page
        return redirect()->route('post.show', $id);
    }

    // delete post
    public function destroy($id)
    {
       $this->post->destroy($id);

        # redirect to show post page
        return redirect()->route('index');
    }
}