@extends('welcome')

@section('seo_title') @lang('navigation.messages') - @endsection

@section('content')

<div class="white-smoke-bg pt-4 pb-3">
  <div class="container no-padding">
    <div id="vue-messages-app" data-user-images='@json($userImages)'>
      <!-- Vue component will be mounted here -->
    </div>
  </div>
</div>

@endsection

@push('extraJS')
<script src="{{ asset('resources/vueapp/dist/vuejs-bundle-v2.1.js') }}"></script>
@endpush
