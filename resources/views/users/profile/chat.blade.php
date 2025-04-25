@extends('layouts.app')

@section('title', 'Chat')

@section('content')
  @include('users.profile.header')
  <div class="margin-top:100px">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card">
          <div class="card-body overflow-auto" style="height: 400px">
              @foreach ($chats as $chat)
                  <div class="row">
                      {{-- Sent Messages --}}
                      @if ($chat->sender_id == Auth::user()->id  && $chat->receiver_id == $user->id)
                      <div class="col-auto ms-auto mb-2 text-end" style="max-width: 70%">
                        <div class="bg-info text-white rounded rounded-1 px-2 py-1 align-middle autofocus">
                            @if ($chat->message)
                                {{ $chat->message }}
                            @endif
                        
                            @if ($chat->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $chat->image) }}" alt="Sent image" class="img-fluid rounded" style="max-height: 200px;">
                                </div>
                            @endif
                        </div>
                    
                        {{-- ✅ Read Receipt --}}
                        <div class="mt-1 small">
                           @if ($chat->read_at)
                                <span class="text-muted small">
                                    ✔ Read {{ \Carbon\Carbon::parse($chat->read_at)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-muted small">✔ Sent</span>
                            @endif
                        </div>
                    </div>
                    
                      {{-- Received Messages --}}
                      @elseif($chat->sender_id == $user->id && $chat->receiver_id == Auth::user()->id)
                          <div class="row col-8 me-auto mb-2 ps-0" >
                              <div class="col-1 w-25 pe-0 me-0 text-center">
                                  @if ($user->avatar)
                                      <img src="{{ $user->avatar }}" alt="{{ $user->avatar }}" class="avatar-sm rounded-circle align-middle">
                                  @else
                                      <i class="fa-solid fa-user avatar-sm text-white bg-secondary rounded-circle text-center pt-2 align-middle"></i>
                                  @endif
                              </div>

                              <div class="col-auto ps-0" style="max-width: 70%">
                                    <div class="rounded rounded-1 px-2 py-1 align-middle bg-secondary text-white autofocus">
                                        @if ($chat->message)
                                            {{ $chat->message }}
                                        @endif
                                    
                                        @if ($chat->image)
                                            <div class="mt-2">
                                                <img src="{{ asset('storage/' . $chat->image) }}" class="img-fluid rounded" style="max-width: 200px;" alt="Received image">
                                            </div>
                                        @endif
                                    </div>                                
                              </div>
                          </div>
                      @endif
                  </div>
              @endforeach
          </div>
          <div class="card-footer bg-white">
            <form action="{{ route('profile.chats.store', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="input-group">

                    {{-- Image Upload --}}
                    <label for="chat_image" class="btn btn-outline-secondary">
                        <i class="fa-regular fa-image"></i>
                    </label>
                    <input type="file" name="chat_image" id="chat_image" class="d-none" accept="image/*">
                    <input type="text" name="chat_message" class="form-control" placeholder="Type a message...">
                   
                    {{-- Send Button --}}
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>        
          </div>
        </div>
      </div>
    </div>
  </div>


@endsection