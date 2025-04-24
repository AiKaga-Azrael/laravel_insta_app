<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // Gates are simply closures that determine if a user is authorized to perform a  given action.
        Gate::define('admin', function($user){ // $user is same as Auth::user() -- authenticated user
            return $user->role_id === User::ADMIN_ROLE_ID;
        });

        View::composer('layouts.app', function ($view) {
            if (Auth::check()) 
            {
                $unreadMessages = Chat::where('receiver_id', Auth::user()->id)
                        ->where('status', 1)
                        ->with('sender')
                        ->orderByDesc('id') 
                        ->latest()
                        ->take(5)
                        ->get();

                $unreadCount = Chat::where('receiver_id', Auth::user()->id)
                        ->where('status', 1)
                        ->count();

                $view->with('unreadMessages', $unreadMessages)
                        ->with('unreadCount', $unreadCount);
            }
        });


        View::composer('*', function ($view) {

            if (Auth::check()) {
                $user = Auth::user();
                logger('User class: ' . get_class($user)); // ← check your logs
                logger('Available methods: ', get_class_methods($user));
            }

            $requestCount = 0;
        
            if (Auth::check() && method_exists(Auth::user(), 'pendingFollowers')) {
                $requestCount = Auth::user()->pendingFollowers()->count();
            }
        
            $view->with('requestCount', $requestCount);
        });


}
}
        

