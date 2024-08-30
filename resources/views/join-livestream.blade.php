@extends( 'welcome' )

@section( 'content' )



<head>
  <meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>IVS Live Stream Playback</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.css" />
  <style>
    body {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background-color: #000;
    }

    #video-player {
      width: 100%;
      max-width: 1000px;
      height: auto;
      background: #000;
    }

    .comment-box {
      width: 100%;
      max-width: 1000px;
      height: 300px;
      overflow-y: auto;
      background-color: #fff;
      margin-top: 10px;
      padding: 10px;
      box-sizing: border-box;
    }

    .comment-input {
      width: calc(100% - 110px);
      padding: 10px;
      margin-top: 10px;
      margin-right: 10px;
    }

    #postComment {
      padding-left: 10px;
      padding-right: 10px;
      margin-top: 10px;
    }

    .like-section {
      margin-left: 20px;
     margin-bottom: 10px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .postLike {
      cursor: pointer;
      color:#d88889;
      
    }
    .postLike:hover {
      cursor: pointer;
      color:#d88889;
      
    }
.comment-button{
  background-color:#7776a3;
  padding: 0;
}
.comment-button:hover{
  background-color:#7776a3;
  padding: 0;
}
.tip{
  margin-top:10px;
  margin-left: 20px;
  margin-bottom: 40px;
}
  </style>
</head>

<body>
<div class="white-smoke-bg pt-4 pb-3">
<div class="container add-padding">
<div class="row">
  <video id="video-player" controls></video>
  <p id="error-message" style="color: red; text-align: center;"></p>

  <!-- Live Chat Section -->
  <section class="container">
    <label for="comments">Live Chat</label>
    <div id="commentsBox" class="comment-box"></div>
    <input type="text" id="commentInput" class="comment-input" placeholder="Type your comment..." />
    <button id="postComment" onclick="postComment()" class="comment-button">Post Comment</button>
  </section>

  <!-- Like Button Section -->
  <div class="like-section">
  <h4 class="d-inline">
			<a href="@if(auth()->check()) javascript:void(0); @else {{ route( 'login' ) }} @endif" class="noHover postLike @if(auth()->check()) lovePost @endif @if( auth()->check() && auth()->user()->hasLiked( $post )) d-none @endif" data-id="{{ $post->id }}">
				<i class="far fa-heart"></i> <span class="post-likes-count" data-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
			</a>
		<a href="javascript:void(0);"
   class="noHover postLike unlovePost @if( (auth()->check() && !auth()->user()->hasLiked( $post )) OR !auth()->check() ) d-none @endif"
   data-id="{{ $post->id }}">
   <i class="fas fa-heart"></i> <span class="post-likes-count" data-id="{{ $post->id }}">{{ $post->likes->count() }}</span>
</a>

		</h4>
  </div>
  <div class="tip">
    @include('tips.tip-form')
  </div>
</div><!-- paddin top 5-->
</div><!-- ./container -->
</div><!-- .swhite-smoke -->

  <script src="https://player.live-video.net/1.30.0/amazon-ivs-player.min.js"></script>
  <script>
    var postId = '{{ $post_id }}';
    document.addEventListener('DOMContentLoaded', () => {
      
      const videoPlayer = document.getElementById('video-player');
      const player = IVSPlayer.create();
      player.attachHTMLVideoElement(videoPlayer);

      const playbackUrl = '{{ $playback_url }}';
      

      player.load(playbackUrl);
      player.play().catch(error => {
        console.error('Error playing the stream:', error);
      });

      player.addEventListener(IVSPlayer.PlayerEventType.PLAYBACK_READY, () => {
        console.log('Playback ready');
      });

      player.addEventListener(IVSPlayer.PlayerEventType.ERROR, (error) => {
        console.error('Player error:', error);
      });

     
    });
    function loadComments() {
        fetch(`/comment/${postId}`)
          .then(response => response.json())
          .then(data => {
            if (data.view) {
              document.getElementById('commentsBox').innerHTML = data.view;
            } else {
              console.error('Failed to load comments.');
            }
          })
          .catch(error => console.error('Error:', error));
      }

      function postComment() {
        const message = document.getElementById('commentInput').value;
        if (!message.trim()) return;

        fetch(`/comment/${postId}`, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ message })
        })
          .then(response => response.json())
          .then(data => {
            if (data.message === 'posted') {
              document.getElementById('commentInput').value = '';
              loadComments();
            } else {
              console.error('Failed to post comment.');
            }
          })
          .catch(error => console.error('Error:', error));
      }

      function handleLike() {
        document.querySelectorAll('.postLike').forEach(button => {
          button.addEventListener('click', function() {
            const postId = this.dataset.id;
            fetch(`/like/${postId}`, {
              method: 'POST',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
              }
            })
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  this.classList.add('d-none');
                  const likesCountSpan = document.querySelector(`.post-likes-count[data-id="${postId}"]`);
                  likesCountSpan.textContent = data.likesCount;
                } else {
                  console.error('Failed to like the post.');
                }
              })
              .catch(error => console.error('Error:', error));
          });
        });
      }

      handleLike();
      setInterval(loadComments, 5000); // Refresh comments every 5 seconds
  </script>
</body>

@endsection
