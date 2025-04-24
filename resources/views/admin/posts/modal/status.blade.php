{{-- Hide Post --}}
<div class="modal fade" id="hide-post-{{ $post->id }}" tabindex="-1" aria-labelledby="hidePostLabel-{{ $post->id }}" aria-hidden="true">>
  <div class="modal-dialog modal-dialog-scrollable">
      <div class="modal-content border-danger">
          <div class="modal-header border-danger">
              <h3 class="h5 modal-title text-danger" id="hidePostLabel-{{ $post->id }}">
                <i class="fa-solid fa-eye-slash"></i> Hide Post
              </h3>
          </div>
          <div class="modal-body">
              Are you sure you want to hide the post <span class="fw-bold">{{ $post->id }}</span>?
          </div>
          <div class="modal-footer border-0">
              <form action="{{ route('admin.posts.deactivate', $post->id) }}" method="post">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-outline-danger btn-sm" data-bs-dismiss="modal">
                      Cancel
                  </button>
                  <button type="submit" class="btn btn-danger btn-sm">Hide</button>
              </form>
          </div>
      </div>
  </div>
</div>
{{-- Unhide --}}
<div class="modal fade" id="unhide-post-{{ $post->id }}" tabindex="-1" aria-labelledby="unhidePostLabel-{{      $post->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-success">
            <div class="modal-header border-success">
            <h3 class="h5 modal-title text-success" id="unhidePostLabel-{{ $post->id }}">
                <i class="fa-solid fa-eye-check"></i> Unhide Post
            </h3>
            </div>
            <div class="modal-body">
                Are you sure you want to unhide the post <span class="fw-bold">{{ $post->id }}</span>?
            </div>
            <div class="modal-footer border-0">
                <form action="{{ route('admin.posts.activate', $post->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-outline-success btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-success btn-sm">Unhide</button>
                </form>
            </div>
        </div>
  </div>
</div>