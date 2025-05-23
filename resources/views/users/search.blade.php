@extends('layouts.app')

@section('title', 'Explore People')

@section('content')
    <div class="row justify-content-center">
        <div class="col-5">

            <p class="h5 text-muted mb-4 text-center">Search results for "<span class="fw-bold">{{ $search }}</span>"</p>

            @forelse ($users as $user)
                <div class="row-align-items-center mb-3">
                    {{-- avatar --}}
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $user->id)}}">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-sm">                                
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-md"></i>                                
                            @endif
                        </a>
                    </div>

                    {{-- name + email --}}
                    <div class="col ps-0 text-truncate">
                        <a href="{{ route('profile.show', $user->id)}}" class="text-decoration-non text-dark fw-bold"> {{ $user->name }}                          
                        </a>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                    </div>

                    {{-- button follow/following --}}
                    <div class="col-auto">
                        @if ($user->id !== Auth::user()->id)
                            @if ($user->isFollowed())
                                {{-- unfollow user --}}
                                <form action="{{ route('follow.destroy', $user->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-secondary fw-bold btn-sm">Following</button>
                                </form>                                
                            @else
                                {{-- follow user --}}
                                <form action="{{ route('follow.store', $user->id) }}" method="post">
                                    @csrf
                                    @method('')

                                    <button type="submit" class="btn btn-primary btn-sm fw-bold">Follow</button>
                                </form>                      
                            @endif                            
                        @endif
                    </div>
                </div>                
            @empty
                <p class="lead text-muted text-center">No Users Found.</p>                
            @endforelse

        </div>
    </div>


    
@endsection