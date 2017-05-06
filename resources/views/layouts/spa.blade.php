<!DOCTYPE html>
<html>
<head>
    @yield('title')
    <meta name="csrf_token" content="{{csrf_token()}}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{mix('/assets/bundle/app.css')}}">

</head>
<body @if(!Auth::check()) class="cover" @endif>

<div id="root">

</div>

<script src="{{ mix('/assets/bundle/manifest.js') }}"></script>
<script src="{{mix('/assets/bundle/vendor.js')}}"></script>
@if(env('APP_ENV') === 'local')
    <script type="text/javascript" src="{{mix('/assets/bundle/app.js')}}"></script>
@else
    <script type="text/javascript" src="/assets/js/app.min.js"></script>
@endif
</body>
</html>