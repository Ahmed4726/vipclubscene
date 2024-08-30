<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #141245;
            margin: 0;
            padding: 20px;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
        }
        .creator_box {
            width: 100%;
            padding: 15px;
        }
        .card {
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            display: flex;
            flex-direction: row;
            width: 100%;
            height: 350px; /* Fixed height for the cards */
            box-sizing: border-box;
        }
        .left-section, .right-section {
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
        }
        .left-section {
            background: #f1f1f1;
            width: 60%; /* Changed from 40% */
        }
        .left-section img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }
        .left-section .name {
            margin-top: 10px;
            font-size: 18px;
            font-weight: bold;
        }
        .left-section .title {
            font-size: 14px;
            color: #888;
        }
        .left-section button {
            margin: 10px 0;
            padding: 10px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 14px;
        }
        .left-section .follow-btn {
            background-color: #3b5998;
            color: #ffffff;
        }
        .left-section .text-btn {
            background-color: #ffffff;
            color: #3b5998;
            border: 1px solid #3b5998;
        }
        .right-section {
            background: #7776a3;
            width: 40%; /* Changed from 60% */
            color: #ffffff;
        }
        .right-section .info {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .right-section .info span {
            font-size: 24px;
            font-weight: bold;
        }
        .right-section .info p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="row">
        @foreach($creators as $p)
        <div class="@if(isset($cols)) col-lg-{{ $cols }} @else col-lg-4 @endif col-md-6 col-sm-6">
            <div class="creator_box">
                <div class="card">
                    <div class="left-section">
                        <a href="{{ str_replace('@', '', $p->handle) }}">
                            <img src="{{ secure_image($p->profilePic, 95, 95) }}" alt="Profile Picture">
                        </a>
                        <div class="name" style="text-align:center;">
                            <a href="{{ str_replace('@', '', $p->handle) }}">{{ $p->name }}</a>
                        </div>
                        <div class="title">{{ $p->handle }}</div>
                        <div>
                            <a href="javascript:void(0);" id="follow-button-{{ $p->id }}" class="btn btn-secondary btn-sm follow-button" data-user-id="{{ $p->id }}">
                            <!--<i class="fas fa-hand-sparkles mr-1"></i>-->
                            <span class="follow-text">
                                @if(auth()->check() && !auth()->user()->isFollowing($p->user_id))
                                    @lang('profile.subscribe')
                                @elseif(auth()->check() && auth()->user()->isFollowing($p->user_id))
                                    @lang('profile.unsubscribe')
                                @else
                                    @lang('profile.subscribe')
                                @endif
                            </span>
                        </a>
                        <div id="loading-spinner-{{ $p->id }}" class="loading-spinner" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> @lang('profile.pleaseWait')
                        </div>

                            <!--<div id="loading-spinner-{{ $p->id }}" class="loading-spinner" style="display: none;">-->
                            <!--    <i class="fas fa-spinner fa-spin"></i> @lang('profile.pleaseWait')-->
                            <!--</div>-->
                        </div>
                        <a href="{{url('/messages')}}" class="btn text-btn mt-1">Text</a>
                    </div>
                    <div class="right-section">
                        <div class="info" style="border-bottom: 1px solid #fff">
                            <p>Publishes</p>
                            <span>{{ $p->posts->count() }}</span>
                        </div>
                        <div class="info" style="border-bottom: 1px solid #fff">
                            <p>Likes</p>
                            <span>{{ $p->posts->sum(function ($post) { return $post->likes->count(); }) }}</span>
                        </div>
                        <div class="info" style="border-bottom: 1px solid #fff">
                            <p>Followers</p>
                            <span>{{ $p->followers_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="container mt-3">
        @if($creators->links())
            {{ $creators->links() }}
        @endif
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.follow-button').on('click', function() {
        var $button = $(this);
        var $text = $button.find('.follow-text');
        var profileUserId = $button.data('user-id');
        // alert(profileUserId);
        var $spinner = $('#loading-spinner-' + profileUserId);

        $spinner.show();
        $button.hide();

        $.ajax({
            url: '/profile/toggleFollow/' + profileUserId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Assuming toggleFollow returns the updated follow status
                if (response.isFollowing) {
                    $text.text('@lang("profile.unsubscribe")');
                } else {
                    $text.text('@lang("profile.subscribe")');
                }
                $spinner.hide();
                $button.show();
            },
            error: function(xhr) {
                if (xhr.status === 403) {
                    alert(xhr.responseJSON.error);
                } else {
                    alert('An error occurred. Please try again.');
                }
                $spinner.hide();
                $button.show();
            }
        });
    });
});

</script>
</body>


