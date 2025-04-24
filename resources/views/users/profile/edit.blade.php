@extends('layouts.app')

@section('title','Edit Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
        <form action="{{ route('profile.update')}}" method="post" class="bg-white shadow rounded-3 p-5" enctype="multipart/form-data">
          @csrf
          @method('PATCH')

          <h2 class="mb-3 fw-light text-muted h3">Update Profile</h2>

          {{-- avatar --}}
          <div class="row mb-3 align-items-center">
              <div class="col-12 col-md-4">
                  {{-- Display the avatar of the user --}}
                  @if ($user->avatar)
                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" id="avatarPreview" class="rounded-circle p-1 shadow mx-auto avatar-lg">
                  @else
                    <i class="fa-solid fa-circle-user text-secondary d-block text-center icon-lg"></i>                  
                  @endif
              </div>
              <div class="col-12 col-md-auto align-self-end mt-3 mt-md-0">
                  <input type="file" name="avatar" id="avatar" class="form-control form-control-sm mt-1">
                  <div class="form-text">
                      Acceptable formats: jpeg, jpg, png and gif only. <br>
                      Max file size is 1048kb.
                  </div>
                  {{-- error --}}
                  @error('avatar')
                      <p class="text-danger small">{{ $message }}</p>   
                  @enderror
              </div>
          </div>

          {{-- name --}}
          <div class="mb-3">
              <label for="name" class="form-label fw-bold">Name</label>
              <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" autofocus>
              {{-- error --}}
              @error('name')
                  <p class="text-danger small">{{ $message }}</p>   
              @enderror
          </div>

          {{-- email --}}
          <div class="mb-3">
              <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                {{-- error --}}
                @error('email')
                    <p class="text-danger small">{{ $message }}</p>   
                @enderror
          </div>

          {{-- introduction --}}
          <div class="mb-3">
              <label for="introduction" class="form-label fw-bold">Introduction</label>
                <textarea name="introduction" id="introduction" rows="5" class="form-control" placeholder="Describe yourself">{{ old('introduction', $user->introduction) }}</textarea>
                {{-- error --}}
                @error('introduction')
                    <p class="text-danger small">{{ $message }}</p>   
                @enderror
          </div>

          {{-- private or public --}}
          <div class="mb-3">
            <label class="form-label fw-bold d-block">Profile Status</label>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="statusPublic" value="0"
                    {{ old('status', $user->status) === 0 ? 'checked' : '' }}>
                <label class="form-check-label" for="statusPublic">Public</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="status" id="statusPrivate" value="1"
                    {{ old('status', $user->status) === 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="statusPrivate">Private</label>
            </div>

            {{-- error --}}
            @error('status')
                <p class="text-danger small">{{ $message }}</p>   
            @enderror
          </div>      

          <button type="submit" class="btn btn-warning px-5">Save</button>
        </form>
      </div>
    </div>

    <script>
        // Preview selected avatar before form submission
        function previewAvatar(event) {
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('avatarPreview');
                preview.src = reader.result;
            };
            reader.readAsDataURL(file);
        }
      </script>
    
    
@endsection