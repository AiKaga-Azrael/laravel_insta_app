@extends('layouts.app')

@section('title', 'Following')

@section('content')
    @include('users.profile.header')

    {{-- show all the followings here --}}
    <div class="margin-top:100px">
      @if ($user->following->isNotEmpty())
          <div class="row justify-content-center">
              <div class="col-4">
                  <h3 class="text-muted text-center">Following</h3>

                  @foreach ($user->following as $following)
                      <div class="row align-items-center mt-3">
                        {{-- avatar --}}
                        <div class="col-auto">
                            <a href="{{ route('profile.show', $following->following->id) }}">
                                @if ($following->following->avatar)
                                    <img src="{{ $following->following->avatar }}" alt="{{ $following->following->name }}" class="rounded-circle avatar-sm">
                                @else
                                    <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                                @endif
                            </a>
                        </div>
                        {{-- name --}}
                        <div class="col ps-0 text-truncate">
                            <a href="{{ route('profile.show', $following->following->id) }}" class="text-decoration-none text-dark fw-bold">
                                {{ $following->following->name }}
                            </a>
                        </div>
                        {{-- follow/following button --}}
                        {{-- <div class="col-auto text-end">
                            @if ($following->following->id != Auth::user()->id)
                                @if ($following->following->following())
                                  {{-- unfollow user --}}
                                  {{-- <form action="{{ route('follow.destroy', $following->following->id) }}" method="post">
                                    @csrf
                                    @method('DELETE') --}}

                                    {{-- <button type="submit" class="border-0 bg-transpare p-0 text-secondary btn-sm">
                                      Following
                                    </button>
                                  </form>                                        
                                @else --}}
                                  {{-- follow user --}}
                                  {{-- <form action="{{ route('follow.store', $following->following->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="border-0 bg-transparet p-0 text-primary btn-sm">
                                        Follow
                                    </button>
                                  </form>                                        
                                @endif
                            @else
                                
                            @endif

                        </div>  --}}

                        <div class="col-auto text-end">
                            @if ($following->following->id != Auth::id())
                                @if ($following->following->isFollowedBy(Auth::user()))
                                    {{-- unfollow user --}}
                                    <form action="{{ route('follow.destroy', $following->following->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="border-0 bg-transparent p-0 text-secondary btn-sm">
                                            Unfollow
                                        </button>
                                    </form>
                                @else
                                    {{-- follow user --}}
                                    <form action="{{ route('follow.store', $following->following->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="border-0 bg-transparent p-0 text-primary btn-sm">
                                            Follow
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                      </div>
                      
                  @endforeach
              </div>
          </div>
          
      @else
          <h3 class="text-muted text-center">No Following Yet</h3>
      @endif
    </div>

@endsection


