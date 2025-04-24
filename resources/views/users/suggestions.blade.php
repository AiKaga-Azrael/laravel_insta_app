@extends('layouts.app')

@section('title', 'Suggested Users')

@section('content')
    @include('users.profile.header')
    <div class="w-50 mx-auto card bg-white shadow border-0 p-4">
        <div class="row align-items-center mb-3 px-4 py-2">
            <div class="row">
                <div class="col-auto text-center w-100 border-bottom border-2 mb-3">
                    <p class="fw-bold h1 pb-3">Suggested Users</p>
                </div>
            </div>

            @foreach ($suggested_users as $user)
                <div class="row align-items-center mb-3 px-4 py-2 border rounded">
                    {{-- avatar --}}
                    <div class="col-auto">
                        <a href="{{ route('profile.show', $user->id) }}">
                            @if ($user->avatar)
                                <img src="{{ $user->avatar }}" alt="{{ $user->name}}" class="rounded-circle avatar-sm">                                
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-sm"></i>
                            @endif
                        </a>
                    </div>
                    
                    {{-- user info --}}
                    <div class="col">
                        <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none text-dark">
                            <p class="fw-bold mb-0">{{ $user->name }}</p>
                        </a>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                        <p class="text-muted small mb-0">
                            @if($user->followers_count > 0)
                                {{ $user->followers_count }} {{ Str::plural('follower', $user->followers_count) }}
                            @else
                                No followers yet
                            @endif
                        </p>
                    </div>
                    
                    {{-- follow button --}}
                    <div class="col-auto">
                        <form action="{{ route('follow.store', $user->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-primary">Follow</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection