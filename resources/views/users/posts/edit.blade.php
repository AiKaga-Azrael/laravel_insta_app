@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
<form action="{{ route('post.update', $post->id) }}" method="post" enctype="multipart/form-data">
  @csrf
  @method('PATCH')

      {{-- categories --}}
      <div class="mb-3">
            <label for="category" class="form-label d-block fw-bold">
                Category <span class="text-muted fw-normal">(Up to 3)</span>
            </label>

            {{-- 
                $selected_categories = [
                        [1]
                        [2]
                        [3]
                    ];
            --}}
            @foreach ($all_categories as $category)
              <div class="form-check form-check-inline">
                @if (in_array($category->id, $selected_categories))
                <input type="checkbox" name="category[]" id="{{ $category->name }}" class="form-check-input" value="{{ $category->id }}" checked> <!-- square bracket [] is used because the selected categories are now array -->    
                @else
                <input type="checkbox" name="category[]" id="{{ $category->name }}" class="form-check-input" value="{{ $category->id }}" > <!-- square bracket [] is used because the selected categories are now array -->
                @endif
                <label for="{{ $category->name }}" class=" form-check-label">{{ $category->name}}</label>
              </div>
            @endforeach
            {{-- Error message area --}}
            @error('category')
                <div class="text-danger small">{{ $message }}</div>   
            @enderror
                </div>

      {{-- description --}}
      <div class="mb-3">
            <label for="description" class="form-label fw-bold">Description</label>
            <textarea name="description" id="description" rows="3" class="form-control" placeholder="What's on your mind?">{{ old('description', $post->description) }}</textarea>
            {{-- Error message area --}}
            @error('description')
                <div class="text-danger small">{{ $message }}</div>   
            @enderror
      </div>

      {{-- image --}}
      <div class="row mb-4">
            <div class="col-6">
                <label for="image" class="form-label fw-bold">Image</label>
                <img src="{{ $post->image }}" alt="post id {{ $post->id }}" class="img-thumbnail w-100">
                <input type="file" name="image" id="image" class="form-control" aria-describedby="image-info">
                <div class="form-text" id="image-info">
                    The acceptable formats are jpeg, jpg, png and gif only.<br>
                    Max file is 1048kb.
                </div>
                {{-- Error message area --}}
                @error('image')
                    <div class="text-danger small">{{ $message }}</div>   
                @enderror
            </div>
      </div>

      <button type="submit" class="btn btn-warning px-5">Save</button>
      </form>
    
@endsection