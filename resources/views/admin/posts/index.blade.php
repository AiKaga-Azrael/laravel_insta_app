@extends('layouts.app')

@section('title', 'Admin: Posts')
    
@section('content')
<div class="table-responsive">
  <table class="table table-hover align-middle bg-white border text-secondary">
    <thead class="small table-success text-secondary">
      <tr>
            <th></th>
            <th>CATEGORY</th>
            <th>OWNER</th>
            <th>CREATED_AT</th>
            <th>STATUS</th>
            <th></th>
      </tr>
    </thead>
    <tbody>
        @foreach ($all_posts as $post)
            <tr>
                <td>
                    @if ($post->image)
                      <img src="{{ $post->image }}" alt="" class="rounded-circle d-block mx-auto avatar-md">                            
                    @else
                      <i class="fa-solid fa-circle-user text-center icon-md d-block"></i>                  
                    @endif
                </td>
                <td>
                    <a href="{{ route('post.show', $post->id) }}" class="text-decoration-none text-dark fw-bold"> {{ $post->category }}                       
                    </a>
                </td>
                <td>{{ $post->user->name }}</td>
                <td>{{ date('M d, Y', strtotime($post->created_at)) }}</td>
                <td>
                    {{-- $post->trashed() returns TRUE if the user was soft deleted. --}}
                    @if ($post->trashed())
                      <i class="fa-regular fa-circle text-secondary"></i>&nbsp; Hide
                    @else
                      <i class="fa-solid fa-circle text-success"></i>&nbsp; Visible
                    @endif                        
                </td>
                <td>
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>

                            <div class="dropdown-menu">
                                @if ($post->trashed())
                                  <button class="dropdown-item text-success" data-bs-toggle="modal" data-bs-target="#unhide-post-{{ $post->id }}">
                                    <i class="fa-solid fa-eye-check"></i> Unhide {{ $post->id }}
                                  </button> 
                                @else
                                  <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#hide-post-{{ $post->id }}">
                                    <i class="fa-solid fa-eye-slash"></i> Hide {{ $post->id }}
                                  </button> 
                                @endif                                    
                            </div>
                        </div>
                          {{-- include deactivate modal --}}
                          @include('admin.posts.modal.status')
                </td>
            </tr>                
        @endforeach
    </tbody>
  </table>
  <!-- Pagination -->
  <div class="d-flex justify-content-center">
    {{ $all_posts->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection