@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @include('users.profile.header')
    <div style="margin-top:100px;">
        @if ($user->isPrivate() && !Auth::user()->is($user) && !$user->isFollowedBy(Auth::user()))
            {{-- If the profile is private, the viewer is NOT the owner, and NOT a follower --}}
            <div class="alert alert-warning text-center">
                <i class="fa-solid fa-lock"></i> This account is private. Follow to see posts.
            </div>
        @else
            {{-- Profile is public OR viewer is owner OR viewer is a follower --}}
            @if ($user->posts->isNotEmpty())
                <div class="row">
                    @foreach ($user->posts as $post)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <a href="{{ route('post.show', $post->id) }}">
                                <img src="{{ $post->image }}" alt="post id {{ $post->id }}"  class="grid-img">
                            </a>
                        </div>
                    @endforeach
                </div>            
            @else
                <h3 class="text-muted text-center">No Posts Yet</h3>
            @endif
        @endif
    </div>
    
    
    

@endsection