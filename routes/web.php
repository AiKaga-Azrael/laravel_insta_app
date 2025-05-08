<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\PostsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\CategoriesController;
use App\Http\Controllers\ChatController;

Route::get('/migrate-sessions', function () {
    Artisan::call('session:table');
    Artisan::call('migrate', ['--force' => true]);
    return '✅ sessions table created!';
});


// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::group(['middleware' => 'auth'], function(){
    Route::get('/', [HomeController::class, 'index'])->name('index'); // Homepage
    Route::get('/people', [HomeController::class, 'search'])->name('search'); // search
    Route::get('/suggestions',[HomeController::class, 'showSuggestedUsers'])->name('users.suggestions');
   
    
    //POST
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');
    Route::post('/post/store',[PostController::class, 'store'])->name('post.store');
    Route::get('/post/{id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/post/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/post/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/post/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    //comment
    Route::post('/comment/{post_id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::delete('/comment/{comment_id}/destroy', [CommentController::class, 'destroy'])->name('comment.destroy');

    //profile
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');
    //Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');    
    Route::get('/profile/{id}/followers', [ProfileController::class, 'followers'])->name('profile.followers');
    Route::get('/profile/{id}/following', [ProfileController::class, 'following'])->name('profile.following');

    //chats
    Route::get('/profile/{id}/chats', [ChatController::class, 'show'])->name('profile.chats');
    Route::post('/profile/{id}/chats/store', [ChatController::class, 'store'])->name('profile.chats.store');
    Route::get('/chats/{id}/unreadmessages', [ChatController::class, 'index'])->name('chats.unreadmessages');

    // likes
    Route::post('/like/{post_id}/store', [LikeController::class, 'store'])->name('like.store');
    Route::delete('/like/{post_id}/destroy', [LikeController::class, 'destroy'])->name('like.destroy');

    //follow
    // Route::post('/follow/{user_id}/store', [FollowController::class, 'store'])->name('follow.store');
    Route::delete('/follow/{user_id}/destroy', [FollowController::class, 'destroy'])->name('follow.destroy');
    Route::post('/follow/{user_id}', [FollowController::class, 'store'])->name('follow.store');
    // Route::delete('/follow/{user_id}', [FollowController::class, 'destroy'])->name('follow.destroy');
    Route::post('/follow/accept/{follower_id}', [FollowController::class, 'acceptRequest'])->name('follow.accept');
    Route::delete('/follow/reject/{follower_id}', [FollowController::class, 'rejectRequest'])->name('follow.reject');
    Route::get('/friend-requests', [FollowController::class, 'friendRequests'])->name('friend.requests')
     ->middleware('auth');


    // Admin
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'admin'], function(){
        // USERS
        Route::get('/users', [UsersController::class, 'index'])->name('users'); //admin.users
        Route::delete('/users/{id}/deactivate', [UsersController::class, 'deactivate'])->name('users.deactivate');//admin.users.deactivate
        Route::patch('/users/{id}/activate', [UsersController::class, 'activate'])->name('users.activate');//admin.users.activate

        // POSTS
        Route::get('/posts', [PostsController::class, 'index'])->name('posts'); //admin.posts
        Route::delete('/posts/{id}/deactivate', [PostsController::class, 'deactivate'])->name('posts.deactivate');//admin.posts.deactivate
        Route::patch('/posts/{id}/activate', [PostsController::class, 'activate'])->name('posts.activate');//admin.posts.activate

        // CATEGORIES
        Route::get('/categories', [CategoriesController::class, 'index'])->name('categories'); //admin.categories
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('categories.store'); // admin.categories.store
        Route::patch('/categories/{id}/update', [CategoriesController::class, 'update'])->name('categories.update'); // admin.categories.update
        Route::delete('/categories/{id}/destroy', [CategoriesController::class, 'destroy'])->name('categories.destroy'); // admin.categories.destroy

        // SEARCH
        Route::get('/people', [UsersController::class, 'search'])->name('users.search'); // admin.search

    });



});

Route::get('/migrate', function () {
    Artisan::call('migrate', ['--force' => true]);
    return 'Migration done!';
    });


    
    
