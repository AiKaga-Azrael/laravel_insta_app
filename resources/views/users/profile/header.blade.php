<div class="row">
    {{-- avatar/icon --}}
    <div class="col-4">
        {{-- Display the avatar of the user --}}
        @if ($user->avatar)
            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle p-1 shadow mx-auto avatar-lg">
        @else
            <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>
        @endif
    </div>

    <div class="col-8">
        {{-- name & button --}}
        <div class="row mb-3">
            {{-- name --}}
            <div class="col-auto">
                <h2 class="display-6 mb-0">{{ $user->name }}</h2>                    
                <span>
                    @if ($user->status == '1')
                        <i class="fa-solid fa-lock text-secondary me-1"></i> Private
                    @else
                        <i class="fa-solid fa-eye text-success me-1"></i> Public
                    @endif
                </span>
            </div>
            {{-- button --}}
            <div class="col-auto p-2">
                @if (Auth::user()->id === $user->id)
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm fw-bold">Edit Profile</a>
                @else
                    @if ($user->isFollowedBy(Auth::user()))
                        {{-- unfollow user --}}
                        <form action="{{ route('follow.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Following</button>
                        </form>                        
                    @else
                        {{-- follow user --}}
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf

                            <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                        </form> 
                    @endif   
                 
                @endif                       
            </div>
            {{-- message --}}
            @if (Auth::user()->id !== $user->id)
                <div class="col-auto p-2">
                    <a href="{{ route('profile.chats', $user->id) }}" class="btn btn-outline-secondary btn-sm fw-bold">
                        <i class="fa-solid fa-message px-2"></i>
                    </a>
                </div>                
            @endif
        </div>

        {{-- posts/followers/following --}}
        {{-- Show post/follower/following counts only if:
     - profile is public OR
     - viewer is profile owner OR
     - viewer is an approved follower --}}
        @if (!$user->isPrivate() || Auth::user()->is($user) || $user->isFollowedBy(Auth::user()))
            <div class="row mb-3">
                {{-- posts --}}
                <div class="col-auto">
                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                        {{ $user->posts->count() }} {{ Str::plural('post', $user->posts->count()) }}
                    </a>
                </div>

                {{-- followers --}}
                <div class="col-auto">
                    <a href="{{ route('profile.followers', $user->id) }}" class="text-decoration-none text-dark">
                        {{ $user->followers->count() }} {{ Str::plural('follower', $user->followers->count()) }}
                    </a>
                </div>

                {{-- following --}}
                <div class="col-auto">
                    <a href="{{ route('profile.following', $user->id) }}" class="text-decoration-none text-dark">
                        {{ $user->following->count() }} {{ Str::plural('following', $user->following->count()) }}
                    </a>
                </div>
            </div>
        @endif
        <p class="fw-bold">{{ $user->introduction }}</p>
    </div>
</div>