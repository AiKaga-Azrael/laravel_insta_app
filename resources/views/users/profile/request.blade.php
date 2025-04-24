@extends('layouts.app')

@section('title', 'Friend Requests')

@section('content')

<h2 class="text-center text-2xl font-semibold mb-4">Friend Requests</h2>

<div class="row justify-content-center">
    <div class="col-6">
        @forelse($requests as $user)
            <div class="friend-request"> 

                {{-- user name + avatar --}}
                <a href="{{ route('profile.show', $user->id) }}">
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="rounded-circle avatar-sm"> 
                    {{ $user->name }}
                </a>

                {{-- accept + reject button --}}
                <div class="actions">
                    <form action="{{ route('follow.accept', $user->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="accept">Accept</button>
                    </form>

                    <form action="{{ route('follow.reject', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="reject">Reject</button>
                    </form>
                </div>
            </div>
        @empty
            <p>No pending requests.</p>
        @endforelse
    </div>
</div>



@endsection



{{-- @extends('layouts.app')

@section('title', 'Friend Requests')

@section('content')

<h2 class="text-center">Friend Requests</h2>

@forelse($requests as $user)
    <div class="p-3 border rounded mb-2">
        {{ $user->name }}

        <form action="{{ route('follow.accept', $user->id) }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit">Accept</button>
        </form>

        <form action="{{ route('follow.reject', $user->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Reject</button>
        </form>
    </div>
@empty
    <p>No pending requests.</p>
@endforelse

@endsection --}}