@extends('welcome')

@section('content')
    <div class="white-smoke-bg pt-4 pb-3">
        <div class="container add-padding">
            <div class="row">

                @include('posts.sidebar-mobile')

                <div class="col-12 col-md-7">
                    <div class="postsList">
                        @foreach ($posts as $post)
                            @component('posts.single', ['post' => $post]) @endcomponent
                        @endforeach
                    </div>
                </div>

                @include('posts.sidebar-desktop')

            </div><!-- row -->
        </div><!-- container -->
    </div><!-- white-smoke-bg -->
@endsection

@section('seo_title')
    @foreach($posts as $post)
        #{{ $post->id }} -
    @endforeach
@endsection

@push('extraJS')
    @if(auth()->check())
        <script>
            $(function(){
                $('.loadComments').trigger('click');
            });
        </script>
    @endif
@endpush
