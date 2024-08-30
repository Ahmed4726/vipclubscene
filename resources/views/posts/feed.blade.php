@if ($feed->count() > 0)
    <div id="post-container">
        @foreach ($feed as $post)
            @component('posts.single', ['post' => $post]) @endcomponent
        @endforeach
    </div>
    
    {{ $feed->links() }} <!-- Display pagination links -->
@else
    <div class="card p-4 mb-4 text-center">
        <h5 class="text-secondary">
            <i class="fas fa-comment-slash"></i> @lang('post.noPosts', ['handle' => $profile->handle])
        </h5>
    </div>
@endif
