<link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
@if( $post->media_type == 'Image' )

@if($post->postmedia->count())
        
        <div class="row">

            <div class="col-6 col-sm-6 col-md-6 mt-2 mb-2">

                @if( $post->disk == 'backblaze' )
                    <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}" data-gallery="post-{{ $post->id }}">
                        <img src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}" alt="" class="img-fluid"/>
                    </a>
                @else
                    <a href="javascript:void(0);" data-toggle="lightbox" data-remote="{{ \Storage::disk($post->disk)->url($post->media_content) }}" data-gallery="post-{{ $post->id }}">
                        <img src="{{ \Storage::disk($post->disk)->url($post->media_content) }}" alt="" class="img-fluid"/>
                    </a>
                @endif

            </div>
            
            @foreach($post->postmedia as $extraMedia)

                <div class="col-6 col-sm-6 col-md-6 mt-2 mb-2">
                    @if( $post->disk == 'backblaze' )
                        <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $extraMedia->media_content}}" data-gallery="post-{{ $post->id }}">
                            <img src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $extraMedia->media_content}}" alt="" class="img-fluid"/>
                        </a>
                    @else
                        <a href="javascript:void(0);" data-toggle="lightbox" data-remote="{{ \Storage::disk($extraMedia->disk)->url($extraMedia->media_content) }}" data-gallery="post-{{ $post->id }}">
                            <img src="{{ \Storage::disk($extraMedia->disk)->url($extraMedia->media_content) }}" alt="" class="img-fluid"/>
                        </a>
                    @endif
                </div>

            @endforeach
        </div>

    @else

        @if( $post->disk == 'backblaze' )
            <a href="javascript:void(0);" data-toggle="lightbox" data-remote="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}">
                <img src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}" alt="" class="img-fluid"/>
            </a>
        @else
            <a href="javascript:void(0);" data-toggle="lightbox" data-remote="{{ \Storage::disk($post->disk)->url($post->media_content) }}">
                <img src="{{ \Storage::disk($post->disk)->url($post->media_content) }}" alt="" class="img-fluid"/>
            </a>
        @endif

    @endif

@elseif( $post->media_type == 'Video' )

<div class="embed-responsive embed-responsive-16by9">
<video id="video-{{ $post->id }}" controls @if(opt('enableMediaDownload', 'No') == 'No') controlsList="nodownload" @endif preload="metadata" disablePictureInPicture>
    @if( $post->disk == 'backblaze' )
        <source src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}#t=0.5" type="video/mp4" />
    @elseif ($post->disk == 's3')
        <source src="{{ \Storage::disk($post->disk)->url($post->video_url) }}#t=0.5" type="video/mp4" />
        @else
        <source src="{{ 'https://vipclubscene.com' . '/public/uploads/' . $post->video_url }}#t=0.5" type="video/mp4" />
    @endif
    @lang('post.videoTag')
</video>
</div>

@elseif( $post->media_type == 'live' )
{{-- <div class="embed-responsive embed-responsive-16by9"> --}}
    <video id="video-{{ $post->id }}" controls width="657" height="300" class="video-js vjs-default-skin" @if(opt('enableMediaDownload', 'No') == 'No') controlsList="nodownload" @endif preload="metadata" disablePictureInPicture>
        <source type="application/x-mpegURL" src="{{ 'https://dlhgx48j8zr2n.cloudfront.net/' . $post->live_stream_url }}">
    </video>
{{-- </div> --}}


@elseif( $post->media_type == 'Audio' )

<div class="p-2">
<audio class="w-100 mb-4" controls @if(opt('enableMediaDownload', 'No') == 'No') controlsList="nodownload" @endif>
    @if( $post->disk == 'backblaze' )
        <source src="https://{{ opt('BACKBLAZE_BUCKET') . '.' . opt('BACKBLAZE_REGION') . '/' .  $post->media_content}}" type="audio/mp3">
    @else
        <source src="{{ \Storage::disk($post->disk)->url($post->audio_url) }}" type="audio/mp3">
    @endif
    @lang('post.audioTag')
</audio>
</div>

@elseif( $post->media_type == 'ZIP' )

<h5>
    <a href="{{ route('downloadZip', ['post' => $post]) }}" target="_blank" class="ml-4 mb-3">
        <i class="fas fa-file-archive"></i> @lang('v16.zipDownload')
    </a>
</h5><br>

@endif

@push('extraCSS')
<link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
<style>
    .ekko-lightbox-nav-overlay a {
        opacity:1;
        color:black;
    }
.embed-responsive {
    position: relative;
    display: block;
    width: 100%;
    height: 0;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
}

.embed-responsive video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
    object-fit: cover; /* Ensure the video covers the container */
}

</style>
@endpush

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://vjs.zencdn.net/7.2.3/video.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
<script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>

<script>
$(document).ready(function() {
    @if($post->media_type == 'Video' || $post->media_type == 'live')
        var video = document.getElementById('video-{{ $post->id }}');
        video.addEventListener('play', function() {
            $.ajax({
                url: "{{ route('posts.incrementView', ['post' => $post->id]) }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.success) {
                        $('#views-count-{{ $post->id }}').text(response.views);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        });
    @endif
});

var player = videojs('video-{{ $post->id }}');
    player.ready(function() {
        player.play();
    });
</script>
