@extends('layouts.app')

@section('title', 'Unread Messages')

@section('content')

  <h2 class="text-center"> All Unread Messages</h2>

    @forelse($unreadMessages as $group)
            <div class="message-thread text-center">
                <a href="{{route('profile.chats', $group->sender_id) }}" class="">{{ $group->message_count }} message{{ $group->message_count > 1 ? 's' : '' }} from {{ $group->sender->name }} </a>
            </div>
    @empty
        <p class="text-center">All caught up!</p>
@endforelse

@endsection
